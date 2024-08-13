## Introduction

This project is a test assessment. It is made with Laravel and Laradock.

## Installing

Make sure you have PHP, Docker, npm and Composer installed globally on your computer.

1. Clone the repo and enter the project folder:
    ```bash
    git clone git@github.com:PetruSova2004/Sykors.git
    cd Sykors
    ```

2. Install all needed dependencies:
    ```bash
    composer install
    npm install
    ```

3. Create a `.env` file and paste the content from `.env.example`:
    ```bash
    cp .env.example .env
    ```

4. Generate an app key:
    ```bash
    php artisan key:generate
    ```

5. Install Laradock into your project by following the [steps](https://laradock.io/getting-started/).

6. After you have installed Laradock, go to `docker-compose.yml` inside Laradock and set the version at the very
   beginning:
    ```yaml
    version: "3"
    ```

7. Run your project:
    ```bash
    cd laradock/
    docker-compose up -d nginx mysql phpmyadmin workspace
    ```

8. Verify if all needed containers started successfully via:
    ```bash
    docker-compose ps
    ```

9. After running migrations and seeds from docker container:
    ```bash
    docker-compose exec workspace bash
    php artisan migrate
    php artisan db:seed --class=TaskStatusSeeder
    ```

10. Now you need to configure your SMTP credentials for testing email sending for the task scheduler:

    - Go to [Mailtrap](https://mailtrap.io) and sign up for an account if you haven't already.
    - After logging in, navigate to the "Inbox" section and create a new inbox.
    - Copy the SMTP credentials (host, port, username, and password) provided for your inbox.
    - Update your `.env` file with the Mailtrap SMTP credentials. Set the following values:

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=<your-mailtrap-smtp-host>
    MAIL_PORT=<your-mailtrap-smtp-port>
    MAIL_USERNAME=<your-mailtrap-username>
    MAIL_PASSWORD=<your-mailtrap-password>
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=<your-email@example.com>
    MAIL_FROM_NAME="${APP_NAME}"
    ```

    - Replace `<your-mailtrap-smtp-host>`, `<your-mailtrap-smtp-port>`, `<your-mailtrap-username>`, and `<your-mailtrap-password>` with the values obtained from Mailtrap.

    - Save the `.env` file and restart your Docker containers to apply the changes:

    ```bash
    docker-compose restart
    ```

    - You can now test email sending functionality for your task scheduler.

11. You can open the project by accessing [this link](http://localhost/).

12. You can also access phpMyAdmin by going to [http://localhost:8081/](http://localhost:8081/) with the following credentials:
  - **Host**: mysql
  - **Database**: default
  - **User**: default
  - **Password**: secret

## API Documentation

1. **Get All Tasks**

    - **Endpoint:** `GET http://localhost/api/tasks`
    - **Success Response:**
    ```json
    {
        "tasks": [
            {
                "id": 1,
                "url": "http://laradock-nginx-1/api/api-test/success",
                "parameters": {
                    "param1": "value1",
                    "param2": "value2"
                },
                "execution_date": "2024-08-13T12:00:00",
                "status": "in_queue"
            }
        ]
    }
    ```

2. **Create a New Task**

    - **Endpoint:** `POST http://localhost/api/tasks`
    - **Example Request Body:**
    ```json
    {
        "url": "http://laradock-nginx-1/api/api-test/success",
        "parameters": {
            "param1": "value1",
            "param2": "value2"
        },
        "execution_date": "2024-08-13T12:00:00"
    }
    ```

    - **Success Response:**
    ```json
    {
        "message": "Task created successfully",
        "task": {
            "id": 2,
            "url": "http://laradock-nginx-1/api/api-test/success",
            "parameters": {
                "param1": "value1",
                "param2": "value2"
            },
            "execution_date": "2024-08-13T12:00:00",
            "status": "in_queue"
        }
    }
    ```

    - **Notes:**
        - The `url` field determines the task's status:
            - Valid Task: `http://laradock-nginx-1/api/api-test/success`
            - Invalid Task: `http://laradock-nginx-1/api/api-test/error`
            - Exception Task: `http://laradock-nginx-1/api/api-test/exception`

3. **Update Task Status**

    - **Endpoint:** `PUT http://localhost/api/tasks/task/status`
    - **Example Request Body:**
    ```json
    {
        "id": 2,
        "status": "in_queue"
    }
    ```

    - **Success Response:**
    ```json
    {
        "message": "Task status updated successfully",
        "task": {
            "id": 2,
            "url": "http://laradock-nginx-1/api/api-test/success",
            "parameters": {
                "param1": "value1",
                "param2": "value2"
            },
            "execution_date": "2024-08-13T12:00:00",
            "status": "in_queue"
        }
    }
    ```
    - **Notes:**
        - The `status` field must be one of the following values:
            - `completed`
            - `in_queue`
            - `canceled`
            - `paused`

## Scheduler Usage

Once you have created tasks with the status `in_queue` and have your Docker containers running, you need to start the scheduler to process these tasks.

1. **Access the workspace container**:
    ```bash
    docker-compose exec workspace bash
    ```

2. **Start the scheduler**:
   In the workspace container, run the following command to start the Laravel scheduler:
    ```bash
    php artisan schedule:work
    ```

   The scheduler will run every minute and process any tasks in the `in_queue` status. During this time, you can create or update tasks to test the scheduler's functionality.

3. **Task Processing**:
    - Tasks that were in the `in_queue` status will be moved to the `completed` status.
    - Notifications for completed tasks will be sent to your Mailtrap inbox.
    - For invalid tasks, errors will be logged in the Laravel log file located at `storage/logs/laravel.log`.
    - Error messages will also be recorded in the `exception` field of the tasks.

By following these steps, you can ensure that the task scheduler is working correctly and that notifications and error handling are functioning as expected.


## Troubleshooting

If something isn't working as expected, try the following steps:

1. **Check if all containers are running correctly**:
   Verify that all necessary Docker containers are up and running. You should see something similar to this when you run:
    ```bash
    cd laradock/
    docker-compose ps
    ```
   Example of correct output:
    ```
    NAME                          IMAGE                 COMMAND                  SERVICE            CREATED       STATUS       PORTS
    laradock-docker-in-docker-1   docker:20.10-dind     "dockerd-entrypoint.…"   docker-in-docker   8 hours ago   Up 8 hours   2375-2376/tcp
    laradock-mysql-1              laradock-mysql        "docker-entrypoint.s…"   mysql              8 hours ago   Up 8 hours   0.0.0.0:3306->3306/tcp, :::3306->3306/tcp, 33060/tcp
    laradock-nginx-1              laradock-nginx        "/docker-entrypoint.…"   nginx              8 hours ago   Up 8 hours   0.0.0.0:80-81->80-81/tcp, :::80-81->80-81/tcp, 0.0.0.0:443->443/tcp, :::443->443/tcp
    laradock-php-fpm-1            laradock-php-fpm      "docker-php-entrypoi…"   php-fpm            8 hours ago   Up 8 hours   9000/tcp
    laradock-phpmyadmin-1         laradock-phpmyadmin   "/docker-entrypoint.…"   phpmyadmin         8 hours ago   Up 8 hours   0.0.0.0:8081->80/tcp, :::8081->80/tcp
    laradock-redis-1              laradock-redis        "docker-entrypoint.s…"   redis              8 hours ago   Up 8 hours   0.0.0.0:6379->6379/tcp, :::6379->6379/tcp
    laradock-workspace-1          laradock-workspace    "/sbin/my_init"          workspace          8 hours ago   Up 8 hours   0.0.0.0:3000-3001->3000-3001/tcp, :::3000-3001->3000-3001/tcp, 0.0.0.0:4200->4200/tcp, :::4200->4200/tcp, 0.0.0.0:5173->5173/tcp, :::5173->5173/tcp, 0.0.0.0:8080->8080/tcp, :::8080->8080/tcp, 0.0.0.0:2222->22/tcp, :::2222->22/tcp, 0.0.0.0:8001->8000/tcp, :::8001->8000/tcp
    ```

2. **Verify SMTP server settings**:
   Check that you have correctly configured your Mailtrap SMTP credentials in the `.env` file. Ensure the host, port, username, and password are correct.

3. **Check the logs for additional information**:
   If you encounter issues, look into the Laravel log file located at `storage/logs/laravel.log` for detailed error messages and additional information.
