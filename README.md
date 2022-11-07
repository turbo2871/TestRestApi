# TestRestApi

### How to run the project

1. Install Docker (Used Docker Desktop)
2. Add to OS **_hosts_** file: 127.0.0.1 api.local
3. cd docker/nginx-proxy
4. docker-compose up -d
5. cd ../../
6. docker-compose build  --build-arg user=api && docker-compose up -d

### Database

1. docker exec -it database_test_rest_api bash
2. mysql -u root --password=root_password
3. USE api_docker;
4. CREATE TABLE `users` (
   `id` int NOT NULL AUTO_INCREMENT,
   `username` varchar(45) DEFAULT NULL,
   `password` varchar(255) DEFAULT NULL,
   `user_email` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

### Test project

1. Used Chrome ARC extension(Advanced Rest Client with Request to “Use XHR extension” switch + ARC cookie exchange)
2. Api test:
> Users List:
>>     Allow-Methods: GET, Endpoint: https://api.local/user/list?limit=20

> Register(Create) User:
>>     Allow-Methods: POST, Endpoint: https://api.local/user/register
>>     Request: {"username":"Bob", "password":"bobpass", "user_email":"bob@com"}

> Create token (login) User:
>>     Allow-Methods: POST, Endpoint: https://api.local/user/token
>>     Request: {"username": "Greg", "password": "gregpassword"}

> Return user information for user token:
>>     Allow-Methods: POST, Endpoint: https://api.local/user/account
>>     Request: {"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IlRlZCIsInVzZXJfZW1haWwiOiJ0ZWRAY29tIiwiZXhwIjoxNjY3ODA1MDUwfQ.-0_97NO8HsQxMY7G6rCDIXKLUSP6iuDwDUp3iI-koL0"}

> Delete user:
>>     Allow-Methods: DELETE, Endpoint: https://api.local/user/delete
>>     Request: {"id": 34}

> Update user:
>>     Allow-Methods: PUT, Endpoint: https://api.local/user/update
>>     Request: {"id": "2", "username": "Greg", "password": "gregpassword", "user_email": "Greg@com"}