# Ganafus-SMS-Gateway
Ganafus PHP-Based SMS Gateway

This repo is not ready yet.

Project homepage: http://ganafus.ga/

# About


P.s. :
I am not that good in PHP, so do not expect master-type codes.
This repo is supposed to work, not to be an art masterpiece. 
Thanks.

# Requirements
Ganafus SMS Gateway requires Windows (7 or above) to run. Linux and Macintosh currently not supported yet.
## Hardware
* A PC with at least dual core level processor, 1GB RAM and 300MB free disk space for MySQL and Gammu.
* A GSM Modem or GSM Phone as SMS transceiver.

## Software
* Windows 7 or above.
* Gammu SMS Gateway. To really serve SMS processing, Gammu is a must.
* MySQL 5.4/MariaDB 10.0 or newer (must support trigger)
* PHP 5.4 or newer, with MySQL support (mysqli extension)

We recommend to use Uniform Server from http://www.uniformserver.com, a repackaged Apache, PHP and MySQL that support GUI-based virtual host creation. Ganafus was
tested using Uniform Server Zero XI (UniServer Zero XI 11.7.2 and UniController XI V1.2.0)

Before Ganafus SMS Gateway installation, do server environment setup to support PHP with MySQL access capability.

# Installation

* Optional: Create virtual host for Ganafus SMS Gateway.
* Run Apache. Test your server environment and make sure PHP run well and can connect to MySQL using mysqli extension.
* Download and copy Ganafus SMS Gateway files to your Apache document root folder (or in it's sub directory) or to your virtual host document root.
* Use browser to navigate to your http URL.



# Setting Up Modem/Phone

# Hosted and Served Files
## Hosted Files
## Served Files

# Testing

# Troubleshooting

# License