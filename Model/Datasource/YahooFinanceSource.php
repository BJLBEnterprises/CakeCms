<?php
//namespace CakeCms\Model\DataSource;

/**
 * Yahoo Finance DataSource
 *
 * Used for reading from Yahoo Finance, through models.
 *
 * PHP Version 5.x
 *
 * CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'HttpSocket');
class YahooFinanceSource extends DataSource
{
	/**
	 * s = symbol
	 * d = month, end (0-11)
	 * e = day, end (1-31)
	 * f = year, end (four digits)
	 * g = interval (d=daily, w=weekly, m=monthly, v=dividends only)
	 * a = month, start (0-11)
	 * b = day, start (1-31)
	 * c = year, start (four digits)
	 * ignore = extension
	 *
	 * @var string
	 */
	protected $_history_request_format = 'http://ichart.finance.yahoo.com/table.csv?s=%s&d=%d&e=%d&f=%d&g=%s&a=%d&b=%d&c=%d&ignore=.csv';

	/**
	 * s = symbol
	 * f = format (s=symbol, l1=last, d1=date, t1=time, c1=change, o=open, h=high, g=low, v=volume)
	 * e = extension
	 *
	 * @var unknown_type
	 */
	protected $_quote_request_format = 'http://download.finance.yahoo.com/d/quotes.csv?s=%s&f=sl1d1t1c1ohgv&e=.csv';

	protected $_schema = array(
		'history' => array(
			'date' => array(
				'type' => 'datetime',
				'null' => false,
				'key' => 'primary'
			),
			'open' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'high' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'low' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'close' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'volume' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'adj_close' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
		),
		'quote' => array(
			'symbol' => array(
				'type' => 'string',
				'null' => false,
				'key' => 'primary',
				'length' => 5
			),
			'last' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'last_date_time' => array(
				'type' => 'datetime',
				'null' => false,
				'key' => 'primary'
			),
			'amount_change' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10,
			),
			'open' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'high' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'low' => array(
				'type' => 'decimal',
				'null' => false,
				'key' => 'primary',
				'length' => 10
			),
			'volume' => array(
				'type' => 'integer',
				'null' => false,
				'key' => 'primary',
				'length' => 11
			),
		)
	);

	public function __construct($config) {
		$this->connection = new HttpSocket();
		parent::__construct($config);
	}

	public function describe($model) {
		return $this->_schema;
	}

	public function listSources() {
		return array('yahoo finance');
	}

	public function read($model, $queryData = array()) {
		if ( empty($queryData['conditions']['symbol']) || empty($queryData['conditions']['request']) ) {
			return false;
		}

		$url = '';
		switch($queryData['conditions']['request']){
			case 'history':
				$url = sprintf(
					$this->_history_request_format,
					$queryData['conditions']['symbol'],
					$queryData['conditions']['month_end'],
				 	$queryData['conditions']['day_end'],
				 	$queryData['conditions']['year_end'],
				 	$queryData['conditions']['interval'],
					$queryData['conditions']['month_start'],
				 	$queryData['conditions']['day_start'],
				 	$queryData['conditions']['year_start']
					);
				break;
			case 'oquote':
				$url = sprintf('http://finance.yahoo.com/q?s=%s', $queryData['conditions']['symbol']);
				break;
			case 'quote':
				$url = sprintf($this->_quote_request_format, $queryData['conditions']['symbol']);
				break;
		}

		$response = $this->connection->get($url);

		$results = array();

		if (!empty($response)) {
			switch($queryData['conditions']['request']){
				case 'history':
					if(stristr($response, '404 Not Found')){
						$results = 404;
					}
					else{
						$response = explode("\n", trim($response));
						$header = explode(',', $response[0]);
						unset($response[0]);
						foreach($response as $r_index => $row){
							$data = explode(',', $row);
							foreach($data as $d_index => $value){ if (!isset($r_index)) { debug($data); }
								$results[$r_index-1][ $header[$d_index] ] = $value;
							}
						}
					}
					break;
				case 'oquote':
					preg_match('/<div id="yfi_quote_summary_data"(.*?)<\/div>/',$response, $qmatch);
					preg_match_all('/<th(.*?)<\/th>/',$qmatch[0],$kmatch);
					preg_match_all('/<td(.*?)<\/td>/',$qmatch[0],$vmatch);
					debug(array($kmatch[0],$vmatch[0]));
					$oquote = array(
						'open' => $vmatch[0][1],
					);
					exit;
					break;
				case 'quote':
					$values = explode(',',$response);

					$results = array(
						'symbol' => str_replace('"', '', $values[0]),
						'last' => $values[1],
						'last_date_time' => date('Y-m-d H:i:s', strtotime( str_replace('"', '', $values[2]).' '.str_replace('"', '', $values[3]) )),
						'change' => str_replace('+', '', $values[4]),
						'open' => $values[5],
						'high' => $values[6],
						'low' => $values[7],
						'volume' => $values[8]
					);
					break;
			}
		}

		return $results;
	}
}
?>
