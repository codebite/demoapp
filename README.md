Url Benchmark Application
========================

Symfony application created for simple website benchmark purposes.


Installation instructions
-------------------------

Download application files from git repository:

$ git clone https://github.com/codebite/demoapp.git


Open console and go to project folder:

$ cd project_folder

Install required libraries and dependencies using composer

$ composer install

Change application config parameters
------------------------------------
Set your email server parameters in the app/config/parameters.yml

Set application services parameters localised in the app/config/services.yml file

Set parameters for benchmark alert service - responsible for email notification - located under  
app.benchmark_alert.msg_params: section. Set param "to" to your email address.

Ensure you set proper write permission for the app var/logs directory. 


Start php build in web server  
-----------------------------

by running the command from the application folder

$ php bin/console server:run


Running application from CLI
----------------------------

Having web server and email server running,
on the second console go to the project folder and run application using command "app:benchmark-url"

$ php bin/console app:benchmark-url http://benchmarked.url http://competitor1.com http://competitor2.com http://competitorN.com

where http://benchmarked.url is the website url you want to test/benchmark. 
Competitors website urls are given after benchmarked url. Urls must be valid and separated by single space char.

After command execution you should see summary table with results.
According to benchmarked url results, alert notification (via email and sms) may be sent.


Running application in the browser
----------------------------------
Having web server and email server running, open given url: 

http://localhost:8000/benchmark/set


To benchmark your website url fill in form fields presented on the screen and "Run benchmark".

You should see summary table with results. If benchmarked url was slower than competitors you should receive email alert

Benchmark results are also saved in the txt log file. 
Check the results by opening the benchmark.txt log file localised
in the var/logs/benchmark/ folder of the application.


