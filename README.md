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