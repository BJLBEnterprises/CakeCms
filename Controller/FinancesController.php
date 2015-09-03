<?php
//namespace CakeCms\Controller;

App::uses('CakeCmsAppController', 'CakeCms.Controller');
/**
 * Finances Controller
 *
 */
class FinancesController extends CakeCmsAppController
{
	public $name = "Finances";

	public $uses = array('accounts', 'classifications', 'transactions');

	public function beforeFilter()
	{
		parent::beforeFilter();
		//$this->Auth->allowedActions = array('index', 'view');
	}

	public function add($model)
	{
		if (!method_exists($this, '_' . $model . '_add')) {
			$this->redirect('/');
		}
		$this->{'_'. $model . '_add'}($id);


	}

	public function delete($model, $id)
	{
		if (!method_exists($this, '_' . $model . '_delete')) {
			$this->redirect('/');
		}
		$this->{'_' .$model .'_delete'}($id);

	}

	public function edit($model, $id)
	{
		if (!method_exists($this, '_' . $model . '_edit')) {
			$this->redirect('/');
		}
		$this->{'_' . $model .'_edit'}($id);
	}

	public function index($model)
	{
		if (!method_exists($this, '_' . $model)) {
			$this->redirect('/');
		}
		$this->{'_' . $model}();
	}

	public function view($model, $id)
	{
		if (!method_exists($this, '_' . $model . '_view')) {
			$this->redirect('/');
		}
		$this->{'_' . $model . '_view'}($id);
	}

//=====
	private function _account_add()
	{
		if (!empty($this->data)) {
			$this->Account->create();
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.', true));
			}
		}
	}

	private function _account_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Account->delete($id)) {
			$this->Session->setFlash(__('Account deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	private function _account_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Account->read(null, $id);
		}
	}

	private function _account_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		$account = $this->Account->read(null, $id);
		$this->set('account', $account);

		$classifications = $this->Account->Transaction->Classification->find('list');
		$this->set(compact('classifications'));

		$this->paginate = array(
			'conditions' => array('Transaction.account_id' => $id),
			'order' => array('Transaction.date' => 'DESC', 'Transaction.created' => 'DESC'),
			'limit' => 20
		);
		$paginated_transactions = $this->paginate('Transaction');
		$this->set('transactions', $paginated_transactions);

		$count_offset = (($this->params['paging']['Transaction']['page'] - 1) * $this->paginate['limit']);
		$offset_sum_credit = $this->Account->Transaction->query('SELECT SUM(`credit`) AS `sum` FROM (SELECT `credit` FROM `transactions` WHERE `transactions`.`account_id` = '.$id.' ORDER BY `date` DESC, `created` DESC LIMIT '.$count_offset.', '. $this->params['paging']['Transaction']['count'] .') AS `credits`');
		$offset_sum_debit = $this->Account->Transaction->query('SELECT SUM(`debit`) AS `sum` FROM (SELECT `debit` FROM `transactions` WHERE `transactions`.`account_id` = '.$id.' ORDER BY `date` DESC, `created` DESC LIMIT '.$count_offset.', '. $this->params['paging']['Transaction']['count'] .') AS `debits`');
		$offset_balance = $offset_sum_credit[0][0]['sum'] - $offset_sum_debit[0][0]['sum'];
		$this->set('offset_balance', $offset_balance);

		if(strtolower($account['Account']['type']) == 'brokerage'){
			// Pull up all position openings and closings
			$positions = array();
			$opening_position_transactions = $this->Account->Transaction->find('all',array('conditions' => array('Transaction.classification_id' => array(15,18)), 'order' => array('Transaction.date' => 'ASC')));
			$closing_position_transactions = $this->Account->Transaction->find('all',array('conditions' => array('Transaction.classification_id' => array(16,17,21,22)), 'order' => array('Transaction.date' => 'ASC')));
			$bookkeeping_position_transactions = $this->Account->Transaction->find('all',array('conditions' => array('Transaction.classification_id' => array(27)), 'order' => array('Transaction.date' => 'ASC')));
			foreach($opening_position_transactions as $pt_index => $transaction){

				if( empty( $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ] ) ){
					// New symbol position space
					$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ] = array();
				}

				// Setting open positions
				$transaction['Transaction']['quantity_closed'] = null;
				$transaction['Transaction']['date_closed'] = null;
				$transaction['Transaction']['price_closed'] = null;
				$transaction['Transaction']['gain_loss'] = 0;

				foreach($bookkeeping_position_transactions as $_pt_index => $_transaction){
					if(stristr($_transaction['Transaction']['description'], 'Split') &&
						 $transaction['Transaction']['symbol'] == $_transaction['Transaction']['symbol'] &&
						 $transaction['Transaction']['date'] < $_transaction['Transaction']['date']){
						$split_ratio = explode(':', substr($_transaction['Transaction']['description'], strpos($_transaction['Transaction']['description'], 'Split')+6));
						$split_multiple = $split_ratio[0]/$split_ratio[1];
						$transaction['Transaction']['quantity'] *= $split_multiple;
						$transaction['Transaction']['price'] /= $split_multiple;
					}
				}

				switch($transaction['Transaction']['classification_id']){
					case 15: // debit
						$transaction['Transaction']['average_unit_cost'] = $transaction['Transaction']['debit']/$transaction['Transaction']['quantity'];
						break;

					case 18: // credit
						$transaction['Transaction']['average_unit_cost'] = $transaction['Transaction']['credit']/$transaction['Transaction']['quantity'];
						break;
				}
				$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][] = $transaction['Transaction'];
			}

			foreach($closing_position_transactions as $pt_index => $transaction) {

				foreach($positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ] as $index => $position) {

					if( empty($positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['date_closed']) &&
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity_closed'] < $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity'] &&
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['date'] <= $transaction['Transaction']['date'] &&
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['indicator'] == $transaction['Transaction']['indicator'] &&
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['strike_price'] == $transaction['Transaction']['strike_price'] &&
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['expiration'] == $transaction['Transaction']['expiration'] ){

						foreach($bookkeeping_position_transactions as $_pt_index => $_transaction){
							if(stristr($_transaction['Transaction']['description'], 'Split') &&
								$transaction['Transaction']['symbol'] == $_transaction['Transaction']['symbol'] &&
								$transaction['Transaction']['date'] < $_transaction['Transaction']['date']){
								$split_ratio = explode(':', substr($_transaction['Transaction']['description'], strpos($_transaction['Transaction']['description'], 'Split')+6));
								$split_multiple = $split_ratio[0]/$split_ratio[1];
								$transaction['Transaction']['quantity'] *= $split_multiple;
								$transaction['Transaction']['price'] /= $split_multiple;
							}
						}

						$quantity_available = $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity'] - $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity_closed'];

						if($quantity_available < $transaction['Transaction']['quantity']){
							// Closing more than the quantity available
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity_closed'] += $quantity_available;
							$transaction['Transaction']['quantity'] -= $quantity_available;
							$quantity_available -= $quantity_available;
						}
						elseif($quantity_available > $transaction['Transaction']['quantity']){
							// Closing less than the quantity available
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity_closed'] += $transaction['Transaction']['quantity'];
							$quantity_available -= $transaction['Transaction']['quantity'];
							$transaction['Transaction']['quantity'] = 0;
						}
						else {
							// Closing the exact quantity available;
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity_closed'] = $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['quantity'];
							$quantity_available = 0;
							$transaction['Transaction']['quantity'] = 0;
						}

						if($quantity_available == 0){
							// Setting closing date and price
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['date_closed'] = $transaction['Transaction']['date'];
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['price_closed'] = (!empty($transaction['Transaction']['price'])) ? $transaction['Transaction']['price'] : 0.00;
						}

						if($transaction['Transaction']['credit'] > $transaction['Transaction']['debit']){
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['gain_loss'] = ($positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['average_unit_cost'] - $transaction['Transaction']['price']) * $transaction['Transaction']['quantity'];
						}
						elseif($transaction['Transaction']['credit'] < $transaction['Transaction']['debit']){
							$positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['gain_loss'] = ($transaction['Transaction']['price'] - $positions[ $transaction['Transaction']['asset_class'] ][ $transaction['Transaction']['symbol'] ][ $index ]['average_unit_cost']) * $transaction['Transaction']['quantity'];
						}
					}

					if($transaction['Transaction']['quantity'] == 0){
						// Position closed move on to next.
						break;
					}
				}
			}

			App::import('Model', 'YahooFinance');
			$YahooFinance = new YahooFinance();

			ksort($positions['Stock']);
			foreach($positions['Stock'] as $symbol => $stock_positions){

				// Get a current price quote
				$quote = $YahooFinance->find('all', array(
					'conditions' => array(
						'symbol' => str_replace('.', '-', $symbol),
						'request' => 'quote'
				)));

				$sp_count = count($stock_positions);
				foreach($stock_positions as $sp_index => $position){

					if( !empty($positions['Stock'][ $symbol ][ $sp_index ]['date_closed']) ){
						// Removing closed positions
						unset($positions['Stock'][ $symbol ][ $sp_index ]);
						$sp_count--;
						continue;
					}

					// Adding the current quote data and gain loss info
					$positions['Stock'][ $symbol ][ $sp_index ]['price_current'] = $quote['last'];
					$positions['Stock'][ $symbol ][ $sp_index ]['price_last_time'] = $quote['last_date_time'];
					$positions['Stock'][ $symbol ][ $sp_index ]['price_change'] = $quote['change'];
					$positions['Stock'][ $symbol ][ $sp_index ]['price_open'] = $quote['open'];
					$positions['Stock'][ $symbol ][ $sp_index ]['price_high'] = $quote['high'];
					$positions['Stock'][ $symbol ][ $sp_index ]['price_low'] = $quote['low'];
					$positions['Stock'][ $symbol ][ $sp_index ]['volume'] = $quote['volume'];
					$positions['Stock'][ $symbol ][ $sp_index ]['gain_loss'] = ($quote['last'] - $position['average_unit_cost']) * $position['quantity'];
					$positions['Stock'][ $symbol ][ $sp_index ]['market_value'] = $quote['last'] * $position['quantity'];

					if($sp_count > 1){
						// Summing up the group of positions
						if( !isset($positions['Stock'][ $symbol ][ -1 ]) ){
							$positions['Stock'][ $symbol ][ -1 ]['asset_class'] = 'Stock';
							$positions['Stock'][ $symbol ][ -1 ]['symbol'] = $symbol;
							$positions['Stock'][ $symbol ][ -1 ]['price_current'] = $quote['last'];
							$positions['Stock'][ $symbol ][ -1 ]['price_last_time'] = $quote['last_date_time'];
							$positions['Stock'][ $symbol ][ -1 ]['price_change'] = $quote['change'];
							$positions['Stock'][ $symbol ][ -1 ]['price_open'] = $quote['open'];
							$positions['Stock'][ $symbol ][ -1 ]['price_high'] = $quote['high'];
							$positions['Stock'][ $symbol ][ -1 ]['price_low'] = $quote['low'];
							$positions['Stock'][ $symbol ][ -1 ]['volume'] = $quote['volume'];
							$positions['Stock'][ $symbol ][ -1 ]['description'] = $position['description'];
							$positions['Stock'][ $symbol ][ -1 ]['account_id'] = $position['account_id'];
							$positions['Stock'][ $symbol ][ -1 ]['classification_id'] = $position['classification_id'];
							$positions['Stock'][ $symbol ][ -1 ]['expiration'] = $position['expiration'];
							$positions['Stock'][ $symbol ][ -1 ]['strike_price'] = $position['strike_price'];
							$positions['Stock'][ $symbol ][ -1 ]['indicator'] = $position['indicator'];
							$positions['Stock'][ $symbol ][ -1 ]['quantity'] = 0;
							$positions['Stock'][ $symbol ][ -1 ]['quantity_closed'] = 0;
							$positions['Stock'][ $symbol ][ -1 ]['date'] = $position['date'];
							$positions['Stock'][ $symbol ][ -1 ]['date_closed'] = null;
							$positions['Stock'][ $symbol ][ -1 ]['price'] = null;
							$positions['Stock'][ $symbol ][ -1 ]['price_closed'] = null;
							$positions['Stock'][ $symbol ][ -1 ]['fee'] = 0;
							$positions['Stock'][ $symbol ][ -1 ]['debit'] = 0;
							$positions['Stock'][ $symbol ][ -1 ]['credit'] = 0;
						}

						$positions['Stock'][ $symbol ][ -1 ]['quantity'] += $position['quantity'];
						$positions['Stock'][ $symbol ][ -1 ]['quantity_closed'] += $position['quantity_closed'];
						$positions['Stock'][ $symbol ][ -1 ]['date'] = ( !empty($positions['Stock'][ $symbol ][ -1 ]['date']) && strtotime($positions['Stock'][ $symbol ][ -1 ]['date']) > strtotime($position['date']) ) ? $position['date'] : $positions['Stock'][ $symbol ][ -1 ]['date'];
						$positions['Stock'][ $symbol ][ -1 ]['fee'] += $positions['Stock'][ $symbol ][ $sp_index ]['fee'];
						$positions['Stock'][ $symbol ][ -1 ]['debit'] += $positions['Stock'][ $symbol ][ $sp_index ]['debit'];
						$positions['Stock'][ $symbol ][ -1 ]['credit'] += $positions['Stock'][ $symbol ][ $sp_index ]['credit'];
					}
				}

				if( isset($positions['Stock'][ $symbol ][ -1 ]) ){
					$positions['Stock'][ $symbol ][ -1 ]['average_unit_cost'] = (($positions['Stock'][ $symbol ][ -1 ]['debit'])-$positions['Stock'][ $symbol ][ -1 ]['credit'])/$positions['Stock'][ $symbol ][ -1 ]['quantity'];
					$positions['Stock'][ $symbol ][ -1 ]['gain_loss'] = ($quote['last'] - $positions['Stock'][ $symbol ][ -1 ]['average_unit_cost']) * $positions['Stock'][ $symbol ][ -1 ]['quantity'];
					$positions['Stock'][ $symbol ][ -1 ]['market_value'] = $quote['last'] * $positions['Stock'][ $symbol ][ -1 ]['quantity'];
				}

				ksort($positions['Stock'][ $symbol ]);
			}

			ksort($positions['Option']);
			foreach($positions['Option'] as $symbol => $option_positions){

				$op_count = count($option_positions);
				foreach($option_positions as $op_index => $position){

					if( !empty($positions['Option'][ $symbol ][ $op_index ]['date_closed']) ){
						// Removing closed positions
						unset($positions['Option'][ $symbol ][ $op_index ]);
						$op_count--;
						continue;
					}
					else {
						// Get a current price quote
						$quote = $YahooFinance->find('all', array(
							'conditions' => array(
								'symbol' => str_replace('.', '-', $symbol) . date('ymd',strtotime($position['expiration'])) . strtoupper( substr($position['indicator'], 0, 1) ) . str_pad( str_replace('.','',$position['strike_price']), 7, '0', STR_PAD_LEFT) . '0',
								'request' => 'quote'
						)));

						$underlying_quote = $YahooFinance->find('all', array(
							'conditions' => array(
								'symbol' => str_replace('.', '-', $symbol),
								'request' => 'quote'
						)));

						// Adding the current quote data and gain loss info
						$positions['Option'][ $symbol ][ $op_index ]['price_current_underlying'] = $underlying_quote['last'];
						$positions['Option'][ $symbol ][ $op_index ]['price_current'] = $quote['last'];
						$positions['Option'][ $symbol ][ $op_index ]['price_last_time'] = $quote['last_date_time'];
						$positions['Option'][ $symbol ][ $op_index ]['price_change'] = $quote['change'];
						$positions['Option'][ $symbol ][ $op_index ]['price_open'] = $quote['open'];
						$positions['Option'][ $symbol ][ $op_index ]['price_high'] = $quote['high'];
						$positions['Option'][ $symbol ][ $op_index ]['price_low'] = $quote['low'];
						$positions['Option'][ $symbol ][ $op_index ]['volume'] = $quote['volume'];
						$positions['Option'][ $symbol ][ $op_index ]['gain_loss'] = 0.00;
						$positions['Option'][ $symbol ][ $op_index ]['market_value'] = 0.00;

						if($quote['last'] > 0.00){
							$positions['Option'][ $symbol ][ $op_index ]['gain_loss'] = ($quote['last'] - $position['average_unit_cost']) * $position['quantity'];
							$positions['Option'][ $symbol ][ $op_index ]['market_value'] = $quote['last'] * $position['quantity'];
						}
					}

					if($op_count > 1){
						// Summing up the group of positions
						if( !isset($positions['Option'][ $symbol ][ -1 ]) ){
							$positions['Option'][ $symbol ][ -1 ]['asset_class'] = 'Stock';
							$positions['Option'][ $symbol ][ -1 ]['symbol'] = $symbol;
							$positions['Option'][ $symbol ][ -1 ]['price_current'] = $quote['last'];
							$positions['Option'][ $symbol ][ -1 ]['price_last_time'] = $quote['last_date_time'];
							$positions['Option'][ $symbol ][ -1 ]['price_change'] = $quote['change'];
							$positions['Option'][ $symbol ][ -1 ]['price_open'] = $quote['open'];
							$positions['Option'][ $symbol ][ -1 ]['price_high'] = $quote['high'];
							$positions['Option'][ $symbol ][ -1 ]['price_low'] = $quote['low'];
							$positions['Option'][ $symbol ][ -1 ]['volume'] = $quote['volume'];
							$positions['Option'][ $symbol ][ -1 ]['description'] = $position['description'];
							$positions['Option'][ $symbol ][ -1 ]['account_id'] = $position['account_id'];
							$positions['Option'][ $symbol ][ -1 ]['classification_id'] = $position['classification_id'];
							$positions['Option'][ $symbol ][ -1 ]['expiration'] = $position['expiration'];
							$positions['Option'][ $symbol ][ -1 ]['strike_price'] = $position['strike_price'];
							$positions['Option'][ $symbol ][ -1 ]['indicator'] = $position['indicator'];
							$positions['Option'][ $symbol ][ -1 ]['quantity'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['quantity_closed'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['date'] = $position['date'];
							$positions['Option'][ $symbol ][ -1 ]['date_closed'] = null;
							$positions['Option'][ $symbol ][ -1 ]['price'] = null;
							$positions['Option'][ $symbol ][ -1 ]['price_closed'] = null;
							$positions['Option'][ $symbol ][ -1 ]['fee'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['debit'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['credit'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['average_unit_cost'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['gain_loss'] = 0;
							$positions['Option'][ $symbol ][ -1 ]['market_value'] = 0;
						}

						$positions['Option'][ $symbol ][ -1 ]['quantity'] += $position['quantity'];
						$positions['Option'][ $symbol ][ -1 ]['quantity_closed'] += $position['quantity_closed'];
						$positions['Option'][ $symbol ][ -1 ]['date'] = ( !empty($positions['Option'][ $symbol ][ -1 ]['date']) && strtotime($positions['Option'][ $symbol ][ -1 ]['date']) > strtotime($position['date']) ) ? $position['date'] : $positions['Option'][ $symbol ][ -1 ]['date'];
						$positions['Option'][ $symbol ][ -1 ]['fee'] += $positions['Option'][ $symbol ][ $op_index ]['fee'];
						$positions['Option'][ $symbol ][ -1 ]['debit'] += $positions['Option'][ $symbol ][ $op_index ]['debit'];
						$positions['Option'][ $symbol ][ -1 ]['credit'] += $positions['Option'][ $symbol ][ $op_index ]['credit'];
						$positions['Option'][ $symbol ][ -1 ]['average_unit_cost'] += (($positions['Option'][ $symbol ][ -1 ]['debit'])-$positions['Option'][ $symbol ][ -1 ]['credit'])/$positions['Option'][ $symbol ][ -1 ]['quantity'];
						$positions['Option'][ $symbol ][ -1 ]['gain_loss'] += ($quote['last'] - $positions['Option'][ $symbol ][ -1 ]['average_unit_cost']) * $positions['Option'][ $symbol ][ -1 ]['quantity'];
						$positions['Option'][ $symbol ][ -1 ]['market_value'] += $quote['last'] * $positions['Option'][ $symbol ][ -1 ]['quantity'];
					}
				}

				if( isset($positions['Option'][ $symbol ][ -1 ]) ){
					$positions['Option'][ $symbol ][ -1 ]['average_unit_cost'] = (($positions['Option'][ $symbol ][ -1 ]['debit'])-$positions['Option'][ $symbol ][ -1 ]['credit'])/$positions['Option'][ $symbol ][ -1 ]['quantity'];
					$positions['Option'][ $symbol ][ -1 ]['gain_loss'] = ($quote['last'] - $positions['Option'][ $symbol ][ -1 ]['average_unit_cost']) * $positions['Option'][ $symbol ][ -1 ]['quantity'];
					$positions['Option'][ $symbol ][ -1 ]['market_value'] = $quote['last'] * $positions['Option'][ $symbol ][ -1 ]['quantity'];
				}
			}

			ksort($positions);

			$this->set('positions',$positions);
		}
	}

	private function _accounts()
	{
		$this->Account->recursive = 0;
		$paginated_accounts = $this->paginate();
		foreach($paginated_accounts as $pa_index => $account){
			$total_sum_credit = $this->Account->Transaction->query('SELECT SUM(`credit`) AS sum FROM transactions WHERE account_id = '.$account['Account']['id']);
			$total_sum_debit = $this->Account->Transaction->query('SELECT SUM(`debit`) AS sum FROM transactions WHERE account_id = '.$account['Account']['id']);
			$paginated_accounts[ $pa_index ]['Account']['total_balance'] = $total_sum_credit[0][0]['sum'] - $total_sum_debit[0][0]['sum'];
		}
		$this->set('accounts', $paginated_accounts);
	}

	private function _classification_add()
	{
		if (!empty($this->data)) {
			$this->Classification->create();
			if ($this->Classification->save($this->data)) {
				$this->Session->setFlash(__('The classification has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The classification could not be saved. Please, try again.', true));
			}
		}
		$parentClassifications = array('' => '')+$this->Classification->ParentClassification->find('list',
			array(
				'fields' => array(
					'ParentClassification.id',
					'ParentClassification.name'
				),
				'order' => array(
					'ParentClassification.parent_id' => 'ASC',
					'ParentClassification.name' => 'ASC'
					)
			));
		$this->set(compact('parentClassifications'));

		$models = array('' => '')+$this->getModelList();
		$this->set(compact('models'));
	}

	private function _classification_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for classification', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Classification->delete($id)) {
			$this->Session->setFlash(__('Classification deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Classification was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	private function _classification_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid classification', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Classification->save($this->data)) {
				$this->Session->setFlash(__('The classification has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The classification could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Classification->read(null, $id);
		}
		$parentClassifications = array('' => '')+$this->Classification->ParentClassification->find('list',
			array(
				'fields' => array(
					'ParentClassification.id',
					'ParentClassification.name'
				),
				'order' => array(
					'ParentClassification.parent_id' => 'ASC',
					'ParentClassification.name' => 'ASC'
					)
			));
		$this->set(compact('parentClassifications'));

		$models = array('' => '')+$this->getModelList();
		$this->set(compact('models'));

		$foreign_keys = array();
		if(!empty($this->data['Classification']['model'])){
			$model_name = Inflector::camelize( $this->data['Classification']['model'] );
			App::import('Model', $model_name);
			$Model = new $model_name();
			$foreign_keys = $Model->find('list', array('fields' => array($Model->name.'.id',$Model->name.'.name')));
		}

		$foreign_keys = array('' => '')+$foreign_keys;
		$this->set(compact('foreign_keys'));
	}

	private function _classification_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid classification', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('classification', $this->Classification->read(null, $id));
	}

	private function _classifications()
	{
		$this->Classification->recursive = 0;
		$this->set('classifications', $this->paginate());
	}

	private function _transaction_add()
	{
		if (!empty($this->data)) {
			$this->Transaction->create();
			$this->data['Transaction']['credit'] = (empty($this->data['Transaction']['credit'])) ? 0.00 : $this->data['Transaction']['credit'];
			$this->data['Transaction']['debit'] = (empty($this->data['Transaction']['debit'])) ? 0.00 : $this->data['Transaction']['debit'];
			if ($this->Transaction->save($this->data)) {
				$this->Session->setFlash(__('The transaction has been saved', true));
				$this->redirect( array('controller' => 'accounts', 'action' => 'view', $this->data['Transaction']['account_id']), true);
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
			}
		}

		$accounts = $this->Transaction->Account->find('list', array(
			'order' => array(
				'Account.name' => 'ASC'
				)));
		$this->set(compact('accounts'));

		$classifications = $this->Transaction->Classification->find('list', array(
			'order' => array(
				'Classification.parent_id' => 'ASC',
				'Classification.name' => 'ASC'
				)));
		$this->set(compact('classifications'));
	}

	private function _transaction_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transaction', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Transaction->delete($id)) {
			$this->Session->setFlash(__('Transaction deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transaction was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	private function _transaction_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['Transaction']['credit'] = (empty($this->data['Transaction']['credit'])) ? 0.00 : $this->data['Transaction']['credit'];
			$this->data['Transaction']['debit'] = (empty($this->data['Transaction']['debit'])) ? 0.00 : $this->data['Transaction']['debit'];
			if ($this->Transaction->save($this->data)) {
				$this->Session->setFlash(__('The transaction has been saved', true));
				$this->redirect(array('controller' => 'accounts', 'action' => 'view', $this->data['Transaction']['account_id']));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Transaction->read(null, $id);
		}

		$accounts = $this->Transaction->Account->find('list', array(
			'order' => array('Account.name' => 'ASC')
		));
		$this->set(compact('accounts'));

		$classifications = $this->Transaction->Classification->find('list', array(
			'order' => array(
				'Classification.parent_id' => 'ASC',
				'Classification.name' => 'ASC'
				)));
		$this->set(compact('classifications'));
	}

	private function _transaction_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transaction', $this->Transaction->read(null, $id));

		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('accounts'));

		$classifications = $this->Transaction->Classification->find('list');
		$this->set(compact('classifications'));
	}

	private function _transactions()
	{
		$this->Transaction->recursive = 0;
		$this->paginate['order'] = array('Transaction.date' => 'DESC', 'Transaction.created' => 'DESC');
		$paginated_transactions = $this->paginate();
		$this->set('transactions', $paginated_transactions);

		$count_offset = (($this->params['paging']['Transaction']['page'] - 1) * $this->params['paging']['Transaction']['current']);
		$count_limit = ($this->params['paging']['Transaction']['count'] - $count_offset);
		$offset_sum_credit = $this->Transaction->query('SELECT SUM(`credit`) AS `sum` FROM (SELECT `credit` FROM `transactions` ORDER BY `date` DESC, `created` DESC LIMIT '.$count_offset.', '.$count_limit.') AS `credits`');
		$offset_sum_debit = $this->Transaction->query('SELECT SUM(`debit`) AS `sum` FROM (SELECT `debit` FROM `transactions` ORDER BY `date` DESC, `created` DESC LIMIT '.$count_offset.', '.$count_limit.') AS `debits`');
		$offset_balance = $offset_sum_credit[0][0]['sum'] - $offset_sum_debit[0][0]['sum'];
		$this->set('offset_balance', $offset_balance);

		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('accounts'));

		$classifications = $this->Transaction->Classification->find('list');
		$this->set(compact('classifications'));

		$total_sum_credit = $this->Transaction->query('SELECT SUM(`credit`) AS sum FROM transactions');
		$total_sum_debit = $this->Transaction->query('SELECT SUM(`debit`) AS sum FROM transactions');
		$total_balance = $total_sum_credit[0][0]['sum'] - $total_sum_debit[0][0]['sum'];
		$this->set('total_balance', $total_balance);
	}
}
