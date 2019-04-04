## BKM Express PHP SDK

https://github.com/BKMExpress/ServerSDKs/blob/master/release-v1.2.0-higher.5.5.zip adresinde bulunan sıkıştırılmış kütüphanenin, `composer` ile kullanılabilmesini sağlamak amacıyla açılmış bir repo'dur. Kitapyurdu development ekibi tarafından bu kütüphane üzerinde güncellemeler yapılacaktır. Ancak bu kütüphanenin *resmi* olmadığını, bir kopya olduğunu tekrar hatırlatmaktayız ve güncel kalabilmek için BKMExpress dökümanlarını takip etmenizi önermekteyiz.


#### Installation

`composer.json` dosyanıza external repository olarak ekleyin. 

```json
{
...
  "repositories": [
    { "type": "vcs", "url": "https://github.com/kitapyurdu/bkm-express-php" }
  ]
...
}
```

`require` bölümüne kütüphaneyi ekleyin. 

```json
{
...
  "require": {
       ...
       "kitapyurdu/bkm-express-php": "dev-master",
       ...
    },
...
}
```

`composer update` ile kütüphanenin yüklenmesini sağlayabilirsiniz.

*Not1:* `dev-master` sürümünü yüklediğiniz için, `composer` bunun yüklenmesini reddedebilir. (Sürüm numarası kullanmak her zaman daha güvenlidir.)

Eğer `composer` bu şekilde bir hata verirse `composer.json` dosyanıza

```json
"minimum-stability": "dev",
"prefer-stable": true,
```

değerlerini ekleyebilirsiniz. İlerleyen zamanlarda sürüm numarası ile kullanıma geçilecektir.

*Not2:* Eğer diğer bağımlılıkların update olmamasını, sadece bkm-express-php bağımlılığının yüklenmesini istiyorsanız:  `composer update kitapyurdu/bkm-express-php` 

#### Samples

#### Configure


##### Environment
- DEV
- SANDBOX
- PRODUCTION


##### Bex::configure methodu
- Bex sınıfını ayarlamak için kullanılır.

```php
use Bex\easy\Bex;

require_once "../../bex2-sdk-php/src/main/Bex/Bex.php";

$bex = Bex::configure(
    $environment, // DEV, LOCAL, SANDBOX, PRODUCTION
    $merchantId, // BKM Tarafından verilen ID değeri
    $privateKey // BKM tarafından verilen private key değeri
);
```

#### Ticket Oluşturmak
* `NONCE_URL`, `INSTALLMENT_URL`, `$amount` değerlerini bir map dizide alır.
* Burda dikkat edilmesi gerekenler
    - Eğer VPOS tanımlarınızı BKM tarafından otomatik yapılmıyorsa VPOS bilgilerini sizin sağlanmanız gerekmektedir.
    - Taksit hesaplamaları için `INSTALLMENT_URL` göndermelisiniz.
    - Ticket oluşturma işlemi girdi olarak bir map dizi alır ve bu map dizi içerisinde aşağıdaki girdiler olmalıdır.
        - `$amount`
        - `nonceUrl`
        - `installmentUrl`
    - Çıktı olarak ticket ile ilgili aşağıdaki değerleri bir map dizi içerisinde alacaksınız. Bu değerlerin BKM UI uygulamalasına parametre olarak verilmesi gerekmektedir.
        * id
        * path
        * token

* Taksit Hesaplama işlemi yapılacak şekilde ticket oluşturmak
```
...configure Bex
$ticket = $bex->createTicket(array(
        'amount' => $amount, // (zorunlu)
        'nonceUrl' => NONCE_URL, // (zorunlu)
        'installmentUrl' => INSTALLMENT_URL, // (seçimli)
        'orderId' => Sipariş Numarası, // (seçimli)
        'tckn' => array( // (seçimli)
            'no' => TC Kimlik Numarası, // (seçimli)
            'check' => TC Kimlik Numarası Kontrolü // (seçimli)
        ),
        'msisdn' => array( // (seçimli)
            'no' => Telefon Numarası, // (zorunlu)
            'check' => Telefon Numarası Kontrolü, // (seçimli)
        ),
        'campaignCode' => "Kampanya kodu" // (seçimli)
)
);

echo $ticket['id']; // ticket id
echo $ticket['path']; // ticket path
echo $ticket['token']; // ticket token
```


* Taksit hesaplama işlemi olmadan ticket oluşturmak
```
...configure Bex
$ticket = $bex->createTicket(array(
    'amount' => $amount,
    'nonceUrl' => NONCE_URL
)
);

echo $ticket['id']; // ticket id
echo $ticket['path']; // ticket path
echo $ticket['token']; // ticket token
```


#### Ticket Yenileme

```
...configure Bex
$ticket = $bex->refreshTicket(array(
        'amount' => $amount, // (zorunlu)
        'nonceUrl' => NONCE_URL, // (zorunlu)
        'installmentUrl' => INSTALLMENT_URL, // (seçimli)
        'orderId' => Sipariş Numarası, // (seçimli)
        'tckn' => array( // (seçimli)
            'no' => TC Kimlik Numarası, // (seçimli)
            'check' => TC Kimlik Numarası Kontrolü // (seçimli)
        ),
        'msisdn' => array( // (seçimli)
            'no' => Telefon Numarası, // (zorunlu)
            'check' => Telefon Numarası Kontrolü, // (seçimli)
        ),
        'campaignCode' => "Kampanya kodu" // (seçimli)
    )
);

echo $ticket['id']; // ticket id
echo $ticket['path']; // ticket path
echo $ticket['token']; // ticket token
```

#### Sonuç Sorgulama
* Sorgulama işlemi ticket üzerinden yapıldığı için **ticket path** kullanılır.
* Daha önce ticket oluşturulduğunda alınan ticket **path** değeri girdi olarak gönderilir.

```php
...configureBex

$result = $bex->queryTicket($ticketPath)
```


#### Taksit Hesaplama işlemleri

* Taksit işlemi için MoneyUtils düzgün format kullanmanızı sağlar. Format olarak **1300,50** şeklinde olmalıdır.
* VPosUtil **sample2** içerisinde bulabilirsiniz.
* VPosUtil VPos bilgilerinize göre düzenlemeniz gerekir.
* VPos bilgilerinizin aşağıdaki örnekte gönderimini görebilirsiniz.
* **$calback** fonksiyon olarak doldurulması gerekmektedir.
* Girdi: map dizisi olarak alırsınız. **Map dizi** içerisinde ;
    - map dizi
        * 'ticketId'
        * 'totalAmount'
        * 'bin'
        * 'bank'
* Çıktı: Çıktı olarak **dizi** içerisinde bir taksit **map dizisi** dönülmelidir.
    - dizi
        * map dizi
            - numberOfInstallment: Taksit Sırası
            - 'installmentAmount: Taksit Tutarı
            - totalAmount: Toplam tutar
            - vposConfig: **sample2** deki VPosUtil dosyasını inceleyiniz
            - bank: banka kodu.

Örnek: Bu örnekte her zaman 2 taksit olacak şekilde taksitlendirme yapılmıştır.

```
use Bex\util\MoneyUtils;
require_once "./VposUtil.php";

...configure Bex
$bex->installments(function ($installmentArray){
    $installments = array();
        $totalAmountStr = $installmentArray["totalAmount"];
        // iki taksit yapalım
        $installmentAmount = MoneyUtils::formatTurkishLira(
            MoneyUtils::toFloat($totalAmountStr) / 2.0
        );
        // 1.ci taksiti ayarlayalım.
            array_push($installments, array(
                'numberOfInstallment' => 1,
                'installmentAmount' => $installmentAmount,
                'totalAmount' => $totalAmountStr,
                'vposConfig' => VposUtil::prepareVposConfig(
                    $installmentArray['bank']
                )
            ));

            // 2. Taksiti ayarlayalım.
            array_push($installments, array(
                'numberOfInstallment' => 2,
                'installmentAmount' => $installmentAmount,
                'totalAmount' => $totalAmountStr,
                'vposConfig' => VposUtil::prepareVposConfig(
                    $installmentArray['bank']
                )
            ));
            // Taksitleri bir array olarak dönelim.
            return $installments;
});
```


#### Onay İşlemi ( Nonce Approve )
* Nonce işlemi nasıl çalışır ?
 - Öncelikle BKM'den sisteminize onay alma isteği gelir.
 - Bu isteğin alındığında dair sisteminizden BKM'ye cevap dönülür.
 - Ardından BKM'ye onayladım veya onaylamadım şekilde bir istekte bulunulur.
 - Bunun sonucunda BKM ödeme işlemini gerçekleştirir veya gerçekleştirmez.

* Nonce işlemi çift taraflı bir işlem olduğundan SDK içerisinde response flush işlemi kullanılmıştır.
Bu işlemin çalışmabilmesi için aşağıdaki configurasyonları yapmış olmanız gerekmektedir.
    - `php.ini` dosyasında aşağıdaki değerleri off yapmalısınız.
        * `output_buffering = Off`
        * `zlib.output_compression = Off`
    - `Nginx` kullanıyorsanız `nginx.conf` dosyasında
        * `gzip  off;`
        * `proxy_buffering  off;`

* Veya aşağıdaki komutları bex.php içerisinde kullanarak ini ayarlarını değiştirebilirsiniz.
   * `ini_set("output_buffering", 0);`  // off
   * `ini_set("zlib.output_compression", 0);`

flush ile ilgili daha detalı bilgiyi aşağıdaki linkte görebilirsiniz.
https://stackoverflow.com/questions/3133209/how-to-flush-output-after-each-echo-call

*  **callback** `function` : işlemin yapılacağı callback fonksiyonunu belirtir.
    - Girdiler (array)
        * map dizi
            - id // ticket id değerini tutar.
            - path // ticket path değerini tutar.
            - token // ticket token değerini tutar.
            - totalAmount  // toplam tutar  
            - orderId // Sipariş numarası
            - totalAmountWithInstallmentCharge // Vade farkı
            - numberOfInstallments // Taksit Sayısı
            - hash  // Kartın hash değeri
            - tcknMatch // TCKN uyumlu (true, false)
            - msisdnMatch //  Telefon numarası uyumlu.
    - Çıktılar
        * boolean,
            - true => // İşlemi onayladığınızı belirtir.
            - false => işlemi reddettiğinizi belirtir.

Örnek:  Örnekte görüleceği gibi approve fonksiyonunda sizden beklenen bir **callback** `function vermenizdir.
```php
$bex->approve(function ($nonceArray) use ($amount) {
        if ($nonceArray['totalAmount'] === $amount) {
            return true;
        }
    return false;
});
```


#### Örnek

* Sample2 klasörü içerisindeki örneği inceleyebilirsiniz.
* Örnekte README dosyasında işlemler ile ilgili genel açıklamalarını bulabilirsiniz.
