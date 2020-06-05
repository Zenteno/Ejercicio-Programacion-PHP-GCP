# API Rest Example

Ultra fast api rest to handle students and courses using Lumen and Swoole PHP.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.


### Prerequisites

You will need this libraries or you can use Docker, there is a docker-compose file with all ready to go.

```
PHP7.2+
Mysql/Mariadb, Postgresql or SqlServer
Pecl
Composer
```

### Installing

This package relies on **Swoole** extension. Make sure you've installed **Swoole** before using this package. Using this command to install it quickly:

Of course the first thing to do is to clone repository

```
git clone https://github.com/Zenteno/Ejercicio-Programacion-PHP-GCP
```
And then swoole extension

```
pecl install swoole
```

After installing the extension, you will need to edit `php.ini` and add `extension=swoole.so` line before using it.

```
php -i | grep php.ini                      # check the php.ini file location
sudo echo "extension=swoole.so" >> php.ini  # append "extension=swoole.so" to the end of php.ini
php -m | grep swoole                       # check if the Swoole extension has been enabled

```
Install composer dependencies

```
cd Ejercicio-Programacion-PHP-GCP && composer install
```

Generate the dotenv file to connect the database. And add your database connection data. You can use sqlite, mysql/mariadb, postgre and sql server.

```
cp .env.example .env
```
Finally, run the migration command.
```
php artisan migrate
```
After that, everything is good to go and run locally
To test if everything is ok, just run:

```
composer start
```

And open your browser in http://localhost:8080/

## Running the tests

By default the test are executed using an sqlite database, you can change it editing the phpunit.xml.

To run the test:

```
./vendor/bin/phpunit --coverage-text
```

## Deployment

There are 3 ways to deploy this api in production environment:

### 1.- Docker + Docker-Compose ###

The easier way to do it, just open the terminal and run:

```
docker-compose up -d
```
Open your browser on **http://your_ip/**, and... that's all folks.

### 2.- K8s ###

If you have a Kubernetes cluster on premise or on cloud, you can deploy use it also.

By default it will scale the pods to 3 replicas, and expose the service with a load balancer. To edit this, just review the **k8s** folder and edit the env variables.

The default used database is a mysql **Google Cloud SQL** instance, but it can be changed through the secrets value for **Github Actions**.

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: lumen-app
  labels:
    app: lumen-app
spec:
  replicas: 3
  selector:
    matchLabels:
      app: lumen-app
  template:
    metadata:
      labels:
        app: lumen-app
    spec:
      containers:
      - name: lumen-app
        image: IMAGE
        env:
        - name: DB_HOST
          value: "_DB_HOST"
        - name: DB_CONNECTION
          value: "_DB_CONNECTION"
        - name: DB_PORT
          value: "_DB_PORT"
        - name: DB_DATABASE
          value: "_DB_DATABASE"
        - name: DB_USERNAME
          value: "_DB_USERNAME"
        - name: DB_PASSWORD
          value: "_DB_PASSWORD"
```

There's a **Github Action** which will package the project, push the image to a **GCR** account and deploy it in **GKE**.

```yaml
docker-build-and-push:
    needs: laravel-tests
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: docker build
      run: |
        docker build . -t ${{secrets.REGISTRY_URL}}/${{secrets.GKE_PROJECT_ID }}/${{secrets.DOCKER_IMAGE_NAME}}:${GITHUB_SHA::8} -t ${{secrets.REGISTRY_URL}}/${{ secrets.GKE_PROJECT_ID }}/${{secrets.DOCKER_IMAGE_NAME}}:latest
        echo ${{secrets.GCP_KEY_FILE}} | base64 -d > account.json
        cat account.json | docker login -u _json_key --password-stdin https://${{secrets.REGISTRY_URL}}
        docker push ${{secrets.REGISTRY_URL}}/${{ secrets.GKE_PROJECT_ID }}/${{secrets.DOCKER_IMAGE_NAME}}
        docker logout
k8-deploy:
    needs: docker-build-and-push
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: GoogleCloudPlatform/github-actions/setup-gcloud@master
        with:
          version: '290.0.1'
          project_id: ${{ secrets.GKE_PROJECT_ID }}
          service_account_key: ${{ secrets.GCP_KEY_FILE }}
          export_default_credentials: true

      - name: Deploy system to gke
        run: |-
          gcloud --quiet auth configure-docker
          gcloud container clusters get-credentials ${{secrets.GKE_CLUSTER_ID}} --zone ${{secrets.GKE_ZONE}} --project ${{ secrets.GKE_PROJECT_ID }}
          sed -i "s|IMAGE|${{secrets.REGISTRY_URL}}/${{ secrets.GKE_PROJECT_ID }}/${{secrets.DOCKER_IMAGE_NAME}}:${GITHUB_SHA::8}|g" k8s/deployment.yaml
          sed -i "s/_DB_HOST/${{ secrets.DB_HOST }}/g" k8s/deployment.yaml
          sed -i "s/_DB_CONNECTION/${{ secrets.DB_CONNECTION }}/g" k8s/deployment.yaml
          sed -i "s/_DB_PORT/${{ secrets.DB_PORT }}/g" k8s/deployment.yaml
          sed -i "s/_DB_DATABASE/${{ secrets.DB_DATABASE }}/g" k8s/deployment.yaml
          sed -i "s/_DB_USERNAME/${{ secrets.DB_USERNAME }}/g" k8s/deployment.yaml
          sed -i "s/_DB_PASSWORD/${{ secrets.DB_PASSWORD }}/g" k8s/deployment.yaml
          kubectl apply -f k8s/deployment.yaml
          kubectl apply -f k8s/service.yaml
```

Just replace the **secrets values** with your credentials and db connection.

### 2.- GAE ###
You can also deploy it on **Google Application Engine**. The **Github Action** pipeline will deploy it on your GAE project.


```yaml
k8-deploy:
    needs: docker-build-and-push
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: GoogleCloudPlatform/github-actions/setup-gcloud@master
        with:
          version: '290.0.1'
          project_id: ${{ secrets.GKE_PROJECT_ID }}
          service_account_key: ${{ secrets.GCP_KEY_FILE }}
          export_default_credentials: true

      - name: Deploy system to app engine
        run: |-
          rm Dockerfile
          sed -i "s/_DB_CONNECTION/${{ secrets.DB_CONNECTION }}/g" app.yaml
          sed -i "s/_DB_USERNAME/${{ secrets.DB_USERNAME }}/g" app.yaml
          sed -i "s/_DB_PASSWORD/${{ secrets.DB_PASSWORD }}/g" app.yaml
          sed -i "s/_DB_DATABASE/${{ secrets.DB_DATABASE }}/g" app.yaml
          sed -i "s/CLOUD_SQL_CONNECTION_NAME/${{ secrets.CLOUD_SQL_CONNECTION_NAME }}/g" app.yaml
          gcloud app deploy app.yaml -q --promote --stop-previous-version
```

Just replace the **secrets values** with your credentials and db connection.

## Built With

* [Lumen](https://lumen.laravel.com/) 
* [Composer](https://getcomposer.org/)
* [Swoole](https://www.swoole.co.uk/)
* [Eloquent](https://laravel.com/docs/master/eloquent)
* [PHP Jwt Library](https://github.com/lcobucci/jwt)
* [Chilean Bundle](https://github.com/freshworkstudio/ChileanBundle)

## Author

* **Alberto Zenteno**

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Please hire me! I won't let you down </3

