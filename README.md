# Pizzapp
Laravel ve VueJS(NuxtJS) ile geliştirilmiş, Elastic Search destekli OAuth 2.0 RestFUL API altyapısına sahip, geliştirilmeye açık basit bir pizza sipariş sistemidir. 


## Docker ile çalıştırmak için:
- `docker info` komutu ile gelen değerlerden `Total Memory:` değerini kontrol ederek sanal makinenize en az 2 GB RAM ayırdığınızdan emin olun.
- `git clone https://github.com/Deneyim34/pizzapp.git` komutuyla reponun bir klonunu alın.
- Klon aldığınız dosyaya girin ve aşağıdaki yönergeleri takip edin.

### Laravel OAuth 2.0 RestFUL API Kurulumu
- `cd src/pizza-api` komutu ile dosyaya girin.
- `composer install` komutuyla bağımlılıkları yükleyin.
- `docker-machine ls` komutunu çalıştırarak kurulum yapacağınız sanal makinenizin ip adresini alın.
- Klasörde bulunan `php-hosts` adlı dosyayı açın.
- `192.168.99.100 pizzapp.test api.pizzapp.test ngrok.pizzapp.test` satırındaki IP adresini kendi sanal makinenizin IP adresi ile değiştirin.
- Bilgisayarınızdaki 
    - Windows ise `/Windows/System32/drivers/etc/hosts` 
    - Linux ise `/etc/hosts`
    - Mac Os X ise `/private/etc/hosts`
    dosyasını açın.
- Bu dosyaya `192.168.99.100 pizzapp.test api.pizzapp.test ngrok.pizzapp.test` satırlarını, IP adreslerini kendi sanal makinenizin IP adresiyle değiştirerek ekleyin.
- `.env` dosyasındaki `192.168.99.100` IP adresli tüm alanları sanal makinenizin IP adresi ile değiştirin.
- Ardından klon aldığınız dosyaya `/` geri dönün.
- `docker-compose up --build -d` komutu ile kurulumu başlatın.
- `docker exec php php /var/www/html/pizza-api/artisan key:generate` komutunu çalıştırın.
- `docker exec php php /var/www/html/pizza-api/artisan migrate --seed` komutu ile veritabanlarını kurun.
- `docker exec php php /var/www/html/pizza-api/artisan passport:install` komutunu çalıştırın.
- `.env` dosyasındaki `PASSPORT_SECRET` değerini, `Client ID: 2` için tanımlanmış olan `Client secret` değeri ile değiştirin.
- `docker exec php php /var/www/html/pizza-api/artisan search:index` komutu ile aranabilir verileri `Elasticsearch`'te indeksleyin.
- `http://api.pizzapp.test` adresinden api'ye ulaşabilirsiniz.
- `docker exec php /var/www/html/pizza-api/vendor/bin/phpunit /var/www/html/pizza-api/tests/` komutu ile api'nin unit testlerini yapabilirsiniz.

### VueJS(NuxtJS) Frontend UI Kurulumu
- `cd src/pizza-api-ui` komutuysa dosyaya girin.
- `yarn install` komutu ile bağımlılıkları yüklenmesini bekleyin.
- `yarn dev` komutu ile projeyi ayağa kaldırın.
- `http://localhost:3000` veya `http://localhost:3000/admin` adresleri üzerinden lokal olarak projeye ulaşabilirsiniz.
- `Laravel OAuth 2.0 RestFUL API kurulumu` bölümündeki adımları tamamladıysanız `docker-compose build nuxt && docker-compose up -d nuxt` komutu ile projeyi deploy edebilirsiniz.
- Ardından `http://pizzapp.test:3000` veya `http://pizzapp.test:3000/admin` adreslerini kullanarak sanal makineniz üzerinden projeye ulaşabilirsiniz.

### Jenkins, Github Webhook ve Ngrok Kurulumu
- `docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword` komutunu çalıştırın ve ekrana gelen `455341501512421c82f46f7d7bed27d0` benzeri şifreyi kopyalayın.
- `http://pizzapp.test:8080/jenkins` adresine girin ve `Administrator Password` yazan alana, kopyaladığınız şifreyi yapıştırın.
- Gelecek olan ekrandaki `Install suggested plugins` ve `Select plugins to install` seçeneklerinden birini kullanarak kurulumu tamamlayın.
- Jenkins paneline giriş yaptıktan sonra soldaki menüden `Jenkins'i yönet` e tıklayın.
- Gelecek olan sayfadan `Eklentileri Yönet` e tıklayın. Açılan sayfanın üzerindeki `Kullanılabilir` tabına tıklayın.
- Sol üstteki `Filtre` alanına `Github` yazın ve listeyi filtreleyin.
- Listeden `GitHub Integration Plugin` adlı plugin'i bulun ve yükleyin.
- Yükleme sırasında sayfanın altındaki `Yükleme tamamlandığında ve bekleyen bir iş yoksa Jenkins'i yeniden başlat.` checkbox'ını aktif edin ve jenkinsin yeniden başlatılmasını bekleyin.
- Jenkins yeniden başladıktan sonra sol menüden `Yeni Item` a tıklayın.
- Gelen sayfadan item'ı isimlendirin ve `Serbest-stil yazılım projesi yapılandır` a tıklayıp sayfanın altındaki `OK` butonuna tıklayın.
- Sayfadan `GitHub project` i aktif edin ve `Project url` si olarak GitHub repository adresinizi yazın.
- `Kaynak Kodu Yönetimi` bölümünden `Git` i seçin ve `Repository URL` kısmına GitHUb Repository Clone adresinizi yazın.
- `Yapılandırma Tetikleyiciler` bölümünden `GitHub hook trigger for GITScm polling` i seçin.
- `Yapılandır` bölümündeki `Yapılandırma Adımı Ekle` ye tıklayın ve `Shell Çalıştır`ı seçin.
- Açılan alana `docker exec php /var/www/html/pizza-api/vendor/bin/phpunit /var/www/html/pizza-api/tests/Unit/` komutunu girin ve sayfanın altındaki `Kaydet` butonuna basın.
- `docker exec -it ngrok sh` komutunu girin.
- `https://ngrok.com` adresinden yeni bir üyelik alın. Giriş yaptıktan sonra dashboardunuzdaki `3 Connect your account` linkine tıklayarak `23a1sd3f1a56dsf46ad51f3asd1fg65sdf4g3sdf1` benzeri auth tokenınızı kopyalayın.
- `ngrok authtoken 23a1sd3f1a56dsf46ad51f3asd1fg65sdf4g3sdf1` komutunu kendi auth tokenınızı kullanarak çalıştırın. Bu komut config dosyasına tokenınızı kaydedecektir.
- `ngrok http 8080` komutunu çalıştırın. Böylece Jenkins'i web'e açmış olacaksınız.
- `http://ngrok.pizzapp.test:4040` adresine girin ve açılan sayfadaki `http://**********.eu.ngrok.io` benzeri url'yi kopyalayın.
- GitHub repository adresinize gidin ve `Settings` e tıklayın.
- Sol Menüden `Webhooks` a tıklayıp açılan sayfadan `Add Webhook` butonuna tıklayın.
- `Payload URL` kısmına az önce kopyaladığınız ngrok adresini yapıştırın ve sonuna `/jenkins/github-webhook/` ekleyin. 
    - Örnek: `http://**********.eu.ngrok.io/jenkins/github-webhook/`
- `Content Type` kısmını `application/json` olarak seçin ve kaydedin.
- Artık `Jenkins` her GitHub commitinizde projenizi otomatik olarak test edecektir.
- Yaptığınız ayarları kontrol etmek için Jenkinsden yarattığınız Item'ın içine girin ve GitHub Repositorynizdeki `README.md` üzerinde küçük bir değişiklik yapıp kaydedin.
- Jenkins panelinde, soldaki `Yapılandırma Geçmişi` alanında test işleminin otomatik olarak çalışıp çalışmadığını gözlemleyin.

Faydalı olması dileğiyle. :)