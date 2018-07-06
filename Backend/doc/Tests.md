# How to use tests in Run-Control backend with docker

**1) First of all, configurate ``app/config/parameters_test.yml``**
Check if:
  - database_host is setted to ``runctrl_dev_mysql_container``
  - database_name is setted to ``run_control_test``

**2) Generate test environment**
    Run ``./docker/(windows|unix)/tests/create_test_environment.(ps1|sh)``
    This script will do:
  - Fresh database ``run_control_test``
  - Apply migrations to your test database
  - Load fixtures to your test database
  - Load fixtures from ``./test/fixtures/`` for your test database

**3) Start tests with ``./docker/(windows|unix)/tests/run_tests.(ps1|sh)`` script**

# How to create new tests

Just create new php file in ``./tests/api/`` and write your test in it. Codeception will execute all tests in this folder

# How to apply new fixture exclusively for tests
Just place fixture file in ``./tests/fixtures`` and run generate test environment script

