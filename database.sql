-- Felhasználók tábla
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(50) DEFAULT NULL;
    balance decimal(20,8) NOT NULL,
    total_withdrawals decimal(20,8) DEFAULT 0,
    last_activity int(32) NOT NULL,
	 auto_token VARCHAR(64) DEFAULT NULL;
    joined int(32) NOT NULL,
    referred_by INT NOT NULL DEFAULT 0;
    referral_earnings DECIMAL(15,8) NOT NULL DEFAULT 0.00000000,
    energy int(32) DEFAULT 0,
    last_autofaucet DATETIME DEFAULT NULL,
    last_claim int(32) NOT NULL,
    credits deciam(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Kifizetések tábla
CREATE TABLE withdrawals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount decimal(10,8) NOT NULL,
    txid varchar(400) NOT NULL,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Paid', 'Rejected') NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `value` varchar(400) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Add default settings
INSERT INTO `settings` (`id`, `name`, `value`) VALUES 
('1', 'faucet_name', 'CoolFaucet'),
('2', 'autofaucet_reward', '0.00001'), 
('3', 'autofaucet_interval', '30'),
('4', 'referral_percent', '10'),
('5', 'autofocus', 'no'),
('6', 'zerochain_api', ''),
('7', 'zerochain_privatekey', ''),
('8', 'rewardEnergy', '1'),
('9', 'admin_username', 'admin'),
('10', 'admin_password', '$2y$10$W9hvVqLady2ivV791Nz9zOeqvjASvUTYxlcA9kW25EROz1RgjVsai'),
('11', 'min_withdraw', '0.0001'),
('12', 'maintenance', 'on'),
('13', 'banner_header_faucet', ''),
('14', 'banner_footer_faucet', ''),
('15', 'banner_left_faucet', ''),
('16', 'banner_right_faucet', ''),
('17', 'energyshop_status', 'on'),
('18', 'currency_value', ''),
('19', 'faucet_timer', '30'),
('20', 'faucet_daily_limit', '10'),
('21', 'faucet_status', 'on'),
('22', 'hcaptcha_pub_key', ''),
('23', 'hcaptcha_sec_key', ''),
('24', 'faucet_reward', '0.0005'),
('25', 'manual_withdraw', 'on'),
('26', 'banner_header_home', ''),
('27', 'banner_footer_home', ''),
('28', 'banner_header_energyshop', ''),
('29', 'banner_footer_energyshop', ''),
('30', 'banner_header_autofaucet', ''),
('31', 'banner_footer_autofaucet', ''),
('32', 'banner_left_autofaucet', ''),
('33', 'banner_right_autofaucet', ''),
('34', 'banner_header_dashboard', ''),
('35', 'banner_footer_dashboard', ''),
('36', 'autofaucet_status', 'on'),
('37', 'banner_header_withdraw', ''),
('38', 'offerwalls_status', ''),
('39', 'bitcotasks_status', ''),
('40', 'bitcotasks_api', ''),
('41', 'bitcotasks_secret', ''),
('42', 'bitcotasks_bearer_token', ''),
('43', 'bitcotasks_ptc_status', 'on'),
('44', 'bitcotasks_shortlinks_status', 'on'),
('45', 'zerads_ptc_status', 'off'),
('46', 'zerads_id', ''),
('47', 'zerads_exchange_value', ''),
('48', 'zerads_password', ''),
('49', 'cmc_api', '');
-- eddig kész a sql a settingsből

CREATE TABLE `energyshop_packages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `energy_cost` INT NOT NULL,
    `zero_amount` DECIMAL(10,8) NOT NULL
);

CREATE TABLE IF NOT EXISTS `transactions` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(32) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,8) NOT NULL,
  `timestamp` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `withdraw_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `amount` decimal(18,8) NOT NULL,
    `address` varchar(255) NOT NULL,
    `error_message` text NOT NULL,
    `logged_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `offerwall_history` (
  `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(32) unsigned NOT NULL,
  `offerwall` varchar(50) NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `amount` decimal(10,2) DEFAULT '0.00',
  `trans_id` varchar(40) NOT NULL,
  `status` ENUM('Pending', 'Paid', 'Rejected') NOT NULL DEFAULT 'Pending', 
  `available_at` int(32) NOT NULL,
  `claim_time` int(32) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
