# tasklee
example angular php app

######initializing db
sql/init.sql can be used to create the database
the other sql files can be run in order to reinitialize and/or create tables and seed the database

######example command to load
cat 0* | mysql -u tasklee tasklee

######other info
document root is intended to be set as the www folder

Please note that the script will operate in the server timezone and/or configured timezone for php and mysql. Using UTC would be recommended for any kind of production application.
