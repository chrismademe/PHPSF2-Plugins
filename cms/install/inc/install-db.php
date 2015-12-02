<?php

try {

    # Setup Tables
    $pdo->query("CREATE TABLE `attempts` (
      `id` int(11) NOT NULL,
      `ip` varchar(39) NOT NULL,
      `expiredate` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    $pdo->query("CREATE TABLE `config` (
      `setting` varchar(100) NOT NULL,
      `value` varchar(100) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    $pdo->query("CREATE TABLE `requests` (
      `id` int(11) NOT NULL,
      `uid` int(11) NOT NULL,
      `rkey` varchar(20) NOT NULL,
      `expire` datetime NOT NULL,
      `type` varchar(20) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    $pdo->query("CREATE TABLE `sessions` (
      `id` int(11) NOT NULL,
      `uid` int(11) NOT NULL,
      `hash` varchar(40) NOT NULL,
      `expiredate` datetime NOT NULL,
      `ip` varchar(39) NOT NULL,
      `agent` varchar(200) NOT NULL,
      `cookie_crc` varchar(40) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    $pdo->query("CREATE TABLE `sf_media` (
      `ID` int(11) NOT NULL,
      `post` int(11) NOT NULL DEFAULT '0',
      `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `modified` timestamp NULL DEFAULT NULL,
      `filename` varchar(255) NOT NULL,
      `type` varchar(255) NOT NULL DEFAULT 'image',
      `status` int(11) NOT NULL DEFAULT '1'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    $pdo->query("CREATE TABLE `sf_meta` (
      `ID` int(11) NOT NULL,
      `post` int(11) NOT NULL,
      `name` text NOT NULL,
      `value` longtext NOT NULL,
      `status` int(11) NOT NULL DEFAULT '1'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    $pdo->query("CREATE TABLE `sf_posts` (
      `ID` int(11) NOT NULL,
      `author` int(11) NOT NULL DEFAULT '1',
      `alias` varchar(255) NOT NULL,
      `type` varchar(255) NOT NULL DEFAULT 'post',
      `title` text NOT NULL,
      `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `modified` timestamp NULL DEFAULT NULL,
      `snippet` text NOT NULL,
      `content` longtext NOT NULL,
      `parent` int(11) NOT NULL DEFAULT '0',
      `status` int(11) NOT NULL DEFAULT '1'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    $pdo->query("CREATE TABLE `users` (
      `id` int(11) NOT NULL,
      `email` varchar(100) DEFAULT NULL,
      `password` varchar(60) DEFAULT NULL,
      `isactive` tinyint(1) NOT NULL DEFAULT '0',
      `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    # Insert Default Data
    $pdo->query("INSERT INTO `config` (`setting`, `value`) VALUES
    ('attack_mitigation_time', '+30 minutes'),
    ('attempts_before_ban', '30'),
    ('attempts_before_verify', '5'),
    ('bcrypt_cost', '10'),
    ('cookie_domain', NULL),
    ('cookie_forget', '+30 minutes'),
    ('cookie_http', '0'),
    ('cookie_name', 'authID'),
    ('cookie_path', '/'),
    ('cookie_remember', '+1 month'),
    ('cookie_secure', '0'),
    ('emailmessage_suppress_activation', '0'),
    ('emailmessage_suppress_reset', '0'),
    ('password_min_score', '0'),
    ('site_activation_page', 'admin/user/activate'),
    ('site_email', 'tech@searchitlocal.co.uk'),
    ('site_key', 'K9lTRSegOpAfAn!2Ogh2Eh#'),
    ('site_name', 'CMS'),
    ('site_password_reset_page', 'admin/user/reset'),
    ('site_timezone', 'Europe/London'),
    ('site_url', 'http://framework-cms.dev'),
    ('smtp', '0'),
    ('smtp_auth', '1'),
    ('smtp_host', 'smtp.example.com'),
    ('smtp_password', 'password'),
    ('smtp_port', '25'),
    ('smtp_security', NULL),
    ('smtp_username', 'email@example.com'),
    ('table_attempts', 'attempts'),
    ('table_requests', 'requests'),
    ('table_sessions', 'sessions'),
    ('table_users', 'users'),
    ('verify_email_max_length', '100'),
    ('verify_email_min_length', '5'),
    ('verify_email_use_banlist', '1'),
    ('verify_password_min_length', '3');");

    $pdo->query("INSERT INTO `users` (`id`, `email`, `password`, `isactive`, `dt`) VALUES
    (1, 'tech@searchitlocal.co.uk', '$2y$10$3TAv5PDihma.AP7wgAuOrODFX8SHBFMw1nITWNuY8YsyytBPlvXnW', 1, '2015-12-01 09:34:53');");

    # Alter Tables
    $pdo->query("ALTER TABLE `attempts`
      ADD PRIMARY KEY (`id`);");

    $pdo->query("ALTER TABLE `config`
      ADD UNIQUE KEY `setting` (`setting`);");

    $pdo->query("ALTER TABLE `requests`
      ADD PRIMARY KEY (`id`);");

    $pdo->query("ALTER TABLE `sessions`
      ADD PRIMARY KEY (`id`);");

    $pdo->query("ALTER TABLE `sf_media`
      ADD PRIMARY KEY (`ID`);");

    $pdo->query("ALTER TABLE `sf_meta`
      ADD PRIMARY KEY (`ID`);");

    $pdo->query("ALTER TABLE `sf_posts`
      ADD PRIMARY KEY (`ID`);");

    $pdo->query("ALTER TABLE `users`
      ADD PRIMARY KEY (`id`);");

    $pdo->query("ALTER TABLE `attempts`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `requests`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `sessions`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `sf_media`
      MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `sf_meta`
      MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `sf_posts`
      MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;");

    $pdo->query("ALTER TABLE `users`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

    # Success!
    $variables->add('cms_install_database', true);

} catch (PDOException $e) {
    $variables->add('cms_install_database', false);
}
