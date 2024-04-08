# Yomali PHP Assesment

### Set up the project

- Clone the project to your local repository [Link](https://github.com/chrix95/yomali-assessment.git)
- Rename the `.env.sample` file to `.env`
- Update all variables within the `.env` file
```
DATABASE_HOST=
DATABASE_PORT=3306
DATABASE_NAME=
DATABASE_USERNAME=
DATABASE_PASSWORD=
```
- Create database on your local machine and import the dump file `yomali_db.sql` within the root directory
- Install all dependencies
```bash
composer install
```
- Start the project using the command below:
```bash
php -S 127.0.0.1:8000 -t public
```

### Test the endpoint
- Use the url below to retrieve all records grouped by page_url:
```GET
http://127.0.0.1:8000/tracker
```
- Use the url below to create a tracking record:
```POST
http://127.0.0.1:8000/tracker
payload: {
    ip_address: "123.456.789.001",
    url: "https://google.com",
    platform: "macOS",
}
```

### Contributors
- [Email](mailto:engchris95@gmail.com)