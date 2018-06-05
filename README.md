# Lab 8

### Intro
Hey, my love <3

This README for you ;)

So, i make little work on `devops` part and now you can simple work. Hope you will enjoy :)


### Up
To up this backend shit just simple run:
```text
docker-composer up -d --build
```

### Database
To connect to the app db from your localhost just use `localhost:33062` host.
```text
mysql -uroot -proot -hlocalhost:33062
```
Also you could go to db container and work there:
```text
docker exec -ti lab-8_db_1 mysql -uroot -proot
```
Then you could create your beautiful database with your pretty tables.

### Example of mysqli usage
You could find simple example of `mysqli` usage at `src/controllers/php`