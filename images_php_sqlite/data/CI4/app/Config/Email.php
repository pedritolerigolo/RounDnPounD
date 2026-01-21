<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Identité de l'expéditeur
     */
    public string $fromEmail  = 'roundandpoundburgers@gmail.com';
    public string $fromName   = 'RounDnPounD';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';

    /**
     * Protocole SMTP obligatoire pour Gmail
     */
    public string $protocol = 'smtp';

    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * Configuration Serveur Gmail
     */
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'roundandpoundburgers@gmail.com';
    
    /**
     * Utilisez le mot de passe d'application généré par Google (16 caractères)
     */
    public string $SMTPPass = ''; //je donne pas ça

    public int $SMTPPort = 587;
    public int $SMTPTimeout = 60;
    public bool $SMTPKeepAlive = false;

    /**
     * Cryptage TLS requis pour le port 587
     */
    public string $SMTPCrypto = 'tls';

    public bool $wordWrap = true;
    public int $wrapChars = 76;

    /**
     * Type HTML pour permettre les liens cliquables
     */
    public string $mailType = 'html';

    public string $charset = 'UTF-8';
    public bool $validate = false;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}