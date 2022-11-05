# TestRestApi

### How to run the project

1. Install Docker (Used Docker Desktop)
2. cd docker/nginx-proxy
3. docker-compose up -d
4. cd ../../
5. docker-compose build  --build-arg user=api && docker-compose up -d