**Progresto – the proof of concept review and comment API.**

**Installation**

1. create the folder in which you would like to clone the project reactions in our example

2. clone the repository into that folder

git clone https://github.com/plamenbotev/review-saas.git reactions

3. create database reactions
4. import the dump.sql there
mysql -u user -p < dump.sql
5. Install dependencies
composer install
6. after the dependencies are installed, the script will ask for the initial configuration
database_host – most likely will be left as the default value (localhost)
database_port – default value
databse_name – the one created at step 3, in our case reactions
database_user – user name
database_password – password
mailer_transport – defaultmailer_host – default
mailer_user – default
mailer_password – default
secret – some secret token
salt – some secret token

7. Create virtual host in apache for the project. Document root has to be folder web located in our project directory.

8. The project should be active and accessible via the newly created configuration.
For local testing the domain created in the vhost can be added in the os host file.
