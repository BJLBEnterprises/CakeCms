INSERT 'users' ('id', 'username', 'password', 'first_name', 'last_name', 'group_id', 'role')
VALUES (1, 'admin', $passhash, 'Admin', 'Account', 1, 'admin'),
(2, 'manager', $passhash, 'Manager', 'Account', 2, 'manager'),
(3, 'user', $passhash, 'User', 'Account', 3, 'user');