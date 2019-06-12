<?php

namespace Bex\merchant\request\transactions;

class TransactionListRequest
{
    /**
     * BKM tarafından size iletilen ‘id’ bilgisi.
     *
     * @var
     */
    public $id;

    /**
     * 'id’ alanına yazılan değerin BKM tarafına public kısmını ilettiğiniz private key ile şifrelenmiş hali. “SHA-256 with RSA” şifreleme yöntemi ile şifrelenmeli.
     *
     * @var
     */
    public $signature;

    /**
     * 'yy-MM-dd HH:mm’ formatında iletilen işlem sorgu başlangıç tarih değeri.
     *
     * @var
     */
    public $startDate;

    /**
     * 'yy-MM-dd HH:mm’ formatında iletilen işlem sorgu bitiş tarih değeri.
     *
     * @var
     */
    public $endDate;

    /**
     * “0” ya da “1” olarak yollanır. 0:başarısız işlemler 1:başarılı işlemler.
     *
     * @var int
     */
    public $paymentResult;

    /**
     * “” ya da istenilen sayfa değeri yollanır. İşlem listesi ilk sorgusu size toplam satır sayısını döner. 50'şer olarak sayfalayarak sorgulama yapabilirsiniz.
     *
     * @var string
     */
    public $page;

    /**
     * true ya da false olarak yollanır ve tarihe göre sıralamayı seçmenize yarar. null ve false değerleri artan olarak sıralama yapar.
     *
     * @var null
     */
    public $orderType;

    public function __construct($id, $startDate, $endDate, $page = '', $paymentResult = '1', $orderType = null)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->page = $page;
        $this->paymentResult = $paymentResult;
        $this->orderType = $orderType;
    }
}
