# TestRestApi

### How to run the project

1. Install Docker (Used Docker Desktop)
2. Add to OS **_hosts_** file: 127.0.0.1 api.local
3. cd docker/nginx-proxy
4. docker-compose up -d
5. cd ../../
6. docker-compose build  --build-arg user=api && docker-compose up -d