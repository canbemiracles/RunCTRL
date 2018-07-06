Installation guide
===========


**Quick installation**
-----------
**1) Install docker**

https://www.docker.com/community-edition#/download

**2) Open dir with docker 'project_dir/docker', choose your`s OS and run 'start_dev_env' script**

**3) Wait until project is builded and check it here:**

`http://127.0.0.1/`

**Install project with docker (Linux)**
-----------

**1) Download docker**

//TODO

**2) Install docker**

//TODO

**3) Check if docker is working**

`docker run hello-world`

**4) Change working directory to docker folder of a project**

`cd /path/to/project/docker/`

**5) Run file start_dev_env.sh**

`./start_dev_env.sh`

***Important note:** If you get an error like "Permissions denied", give file run permissions (`chmod +x start_dev_env.sh`)*

**6) Wait until project is builded and check it here:**

`http://127.0.0.1/`

***Important note:** you don't need access to app.php (for prod env) and app_dev.php (for dev env). Nginx routes this for you.*

**Another docker scripts**
--------

`start_dev_env.(sh|ps1)` **- Start dev enviroment of project**
`start_prod_env.(sh|ps1)` **- Start prod enviroment of project**

`php_dev_container.(sh|ps1)` **- Get access to dev php container bash (you can use php commands here)**
`php_prod_container.(sh|ps1)` **- Get access to prod php container bash (you can use php commands here)**
***Important note:** containers must be up for accessing php containers. *

`kill-env.(sh|ps1)` **- Killing all enviroments and frees memory**

**Install project with docker (Windows 10)**
-----------

***Important note:** project supports only windows 10 because only this version implements Hyper-V engine.* 

**1) Download docker**

Get stable **version** of docker from here:

`https://docs.docker.com/docker-for-windows/install/#download-docker-for-windows`

**2) Install docker**

Just run .msi file and follow the instructions

**3) Check if docker is working**

**1. Open powershell (Win+R => powershell)**

**2. Run Hello-World image:**

`docker run hello-world`

**4) Close powershell and enter docker folder of a project with windows explorer (/path/to/project/docker/)**

**5) Find file start_dev_env.ps1, right click it and select `Run with powershell`**

**6) Wait until project is builded and check it here:**

`http://127.0.0.1/`

***Important note:** you don't need access to app.php (for prod env) and app_dev.php (for dev env). Nginx routes this for you. *
