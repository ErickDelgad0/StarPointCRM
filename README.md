# StarPointCRM
Built using MySQL, PHP, and Apache; containerized using Docker. This ensures a consistent development, testing, and deployment experience, no matter where it's run.

## Creating the .env file and Running Docker Container
1. Navigate to the root directory of the project.
2. Create a new file named .env.
3. Open the .env file in your preferred text editor.
4. Add the following variables and set their respective values:
    MYSQL_DATABASE=YourDatabaseName
    MYSQL_USER=YourMySQLUsername
    MYSQL_PASSWORD=YourMySQLUserPassword
    MYSQL_ROOT_PASSWORD=YourMySQLRootPassword
   
    GMAIL_USER = YourGmailUsername
    GMAIL_PASSWORD = YourGmailPassword (must be given by gmail)


6. Replace YourDatabaseName, YourMySQLUsername, YourMySQLUserPassword, and YourMySQLRootPassword with your desired values. (Save and Close)
    ⚠️ Notice: The .env file contains sensitive data. Ensure it's added to your .gitignore file
7. Now that you've set up the .env file, you can proceed to start your Docker container using 'docker-compose up'

