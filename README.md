<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**
- 

## 1. instalation docker
    - cek docker version
    $ sudo docker --version
    - jika belum terinstall
    $ sudo apt update && sudo apt upgrade -y
    - Instal dependensi yang diperlukan
    $ sudo apt install apt-transport-https ca-certificates curl software-properties-common -y
    $ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    $ sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"
    $ apt-cache policy docker-ce
    $ sudo apt install docker-ce
    $ sudo systemctl status docker
    $ sudo usermod -aG docker ${USER}
    $ su - ${USER}
    - cek apakah sudah ada group
    $ groups
## 2.Create SSH key Di Server untuk Deploy
        $ sudo adduser deploy
    # silakan install acl jika belum punya
        $ sudo apt install acl
        $ sudo setfacl -R -m u:deploy:rwx /home/apps

    # set permission yang perlu di folder tujuan, saat sudah ada projectnya
        $ chmod 777 -R /home/apps/storage
        $ chmod 777 -R /home/apps/public
    # login sebagai deployer
        $ su deploy
        ## nanti akan jadinya seperti ini
            deploy@arista:/home$
    # buat private key dan public key
        $ ssh-keygen -t rsa
    # copy isi dari public key ke authorized key:
        $ cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
    # masuk folder, buka terminal di /home/apps folder
        $ git clone https://github.com/mallawatech/docker-apache-php8.3-mysql.git .
        $ docker-compose up -d
    # masuk ke container webserver
    - Proses install letsencrypt
        $ docker exec -it webserver bash
        $ certbot --apache -d arista.madignet.com -m mallawaconnection@gmail.com
        $ certbot --apache -d arista.madignet.com -d www.arista.madignet.com -m mallawaconnection@gmail.com
## sampai di sini harusnya sudah bisa berjalan tapi jika masih ada masalah coba langkah di bawah ini
    root@arista:/home/apps# docker ps
    ##pastikan statusnya UP ditiap container ID
    CONTAINER ID   IMAGE            COMMAND                  CREATED          STATUS          PORTS                                                                      NAMES
    5a8465efd894   apps-webserver   "docker-php-entrypoi…"   54 minutes ago   Up 13 minutes   0.0.0.0:80->80/tcp, :::80->80/tcp, 0.0.0.0:443->443/tcp, :::443->443/tcp   webserver
    d9e15d7ab78d   mysql:5.7        "docker-entrypoint.s…"   55 minutes ago   Up 13 minutes   0.0.0.0:3306->3306/tcp, :::3306->3306/tcp, 33060/tcp                       mysql-container
    root@arista:/home/apps# docker run -v /var/run/docker.sock:/var/run/docker.sock -it apps-webserver /bin/bash


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# arista
# arista
