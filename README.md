# Kandilli Rasathanesi API Servisi
Daha öncesinden yazmış olduğumuz [Kandilli Rasathanesi Deprem kütüphane](http://github.com/ZquaRe//Kandilli-Rasathanesi)sini kullanarak gelişmiş bir API servisi oluşturduk.

## Kurulum
application > config > config.php dosyasını açıp $config['base_url'] alanını kendi sitenize göre uyarlayınız.
<br />
```php
//Örnek
$config['base_url'] = 'localhost:81/kandilli-api';
```
## Veritabanı Ayarları
Dosya içerisinde bulunan kandilli.sql dosyasını veritabanınıza yükleyin.<br />application > config > database.php dosyasından veritabanı ayarlarını veritabanınıza göre ayarlayın.
<br />
```php
//Örnek
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '1234',
	'database' => 'kandilli',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```

## Api Kullanım
|   Api Adres      | Açıklama   | 
| ------------- |:-------------:|
| /api      | Türkiye'de gerçekleşmiş son 500 deprem bilgisini getirir. |
| /api/date/14-04-2020      | 14-04-2020 Tarihinde gerçekleşmiş son 500 depremi ekrana getirir.<br />Bütün tarih formatları desteklenmektedir.      | 
| /api/date/14-04-2020/limit/5      | 14-04-2020 Tarihinde gerçekleşmiş son 5 depreme ait bilgileri ekrana getirir.<br />Bütün tarih formatları desteklenmektedir.      | 
| /api/location/istanbul      |  İstanbul lokasyonunda gerçekleşmiş son 500 depreme ait bilgileri ekrana getirir.| 
| /api/location/istanbul/limit/10      |  İstanbul lokasyonunda gerçekleşmiş son 10 depreme ait bilgileri ekrana getirir.| 
| /api/between/14-04-2020/13-04-2020      |  14-04-2020 ve 13-04-2020 tarihleri arasında gerçekleşmiş olan son 500 depremi ekrana getirir.<br />Tam tersi tarih kullanımında da çalışmaktadır. | 
| /api/between/14-04-2020/13-04-2020/limit/5      |  14-04-2020 ve 13-04-2020 tarihleri arasında gerçekleşmiş olan son 5 depremi ekrana getirir.<br />Tam tersi tarih kullanımında da çalışmaktadır. | 
| /api/limit/5      |  Son 5 depreme ait bilgileri ekrana getirir.| 
| /api/cronjob     |  Kandilli rasathanesinden aldığı verileri veritabanına otomatik olarak ekler,<br />Verilerin veritabanına otomatik olarak eklenmesi için sitenizin zamanlayıcıyı (Cronjob) ayarlamanız gerekmektedir. | 

## Kaynak
Elde edilen veriler BOĞAZİÇİ ÜNİVERSİTESİ KANDİLLİ RASATHANESİ VE DEPREM ARAŞTIRMA ENSTİTÜSÜ'nden alınmaktadır.<br />
Söz konusu bilgi, veri ve haritalar Boğaziçi Üniversitesi Rektörlüğü’nün yazılı izni ve onayı olmadan herhangi bir şekilde ticari amaçlı kullanılamaz.
 <br />
 Site: http://www.koeri.boun.edu.tr/scripts/lst0.asp

## İletişim
> furkan-sezgin@hotmail.com
