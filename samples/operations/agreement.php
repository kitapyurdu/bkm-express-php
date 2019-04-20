<?php

require_once __DIR__.'/setup.php';
Log::debug('HTTP REQUEST TO => '.__FILE__);
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: small;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
<script>
    function acceptAgreement() {
        window.parent.postMessage("close", "*");
    }
</script>
<div>
    <div style=" padding: 10px;overflow: scroll;-webkit-overflow-scrolling: touch">
        <h5 style="text-align: center;font-weight: bold;letter-spacing: 0.5px">
            Yeni ekledigimiz ozelliklerle birlikte kullanici sozlesmemizi yeniledik.</h5>
        <br/>
        <div style="height: 365px">
            <div>
                <h4>Siparis Bilgisi</h4>
                <table>
                    <tr>
                        <th>
                            Kalem
                        </th>
                        <th>
                            Deger
                        </th>
                    </tr>
                    <tr>
                        <td>ticketId:</td>
                        <td><?php echo $_POST['ticketId']; ?></td>
                    </tr>
                    <tr>
                        <td>orderId:</td>
                        <td> <?php echo $_POST['orderId']; ?></td>
                    </tr>
                    <tr>
                        <td>totalAmount:</td>
                        <td> <?php echo $_POST['amount']; ?></td>
                    </tr>
                </table>
            </div>

            <div>
                <h4>Teslimat Adresi Bilgisi</h4>
                <table>
                    <tr>
                        <th>
                            Kalem
                        </th>
                        <th>
                            Deger
                        </th>
                    </tr>
                    <tr>
                        <td>Adres Tipi:</td>
                        <td><?php echo $_POST['deliveryAddress_addressType']; ?></td>
                    </tr>
                    <tr>
                        <td>Il :</td>
                        <td><?php echo $_POST['deliveryAddress_city']; ?></td>
                    </tr>
                    <tr>
                        <td>Ilce:</td>
                        <td><?php echo $_POST['deliveryAddress_county']; ?></td>
                    </tr>
                    <tr>
                        <td>Acik Adres :</td>
                        <td><?php echo $_POST['deliveryAddress_address']; ?></td>
                    </tr>
                    <tr>
                        <td>Firma Unvani :</td>
                        <td><?php echo $_POST['deliveryAddress_companyName']; ?></td>
                    </tr>
                    <tr>
                        <td>Email :</td>
                        <td><?php echo $_POST['deliveryAddress_email']; ?></td>
                    </tr>
                    <tr>
                        <td>Ad:</td>
                        <td><?php echo $_POST['deliveryAddress_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Soyad:</td>
                        <td><?php echo $_POST['deliveryAddress_surname']; ?></td>
                    </tr>
                    <tr>
                        <td>Vergi Kimlik Numarasi :</td>
                        <td><?php echo $_POST['deliveryAddress_taxNumber']; ?></td>
                    </tr>
                    <tr>
                        <td>Vergi Dairesi</td>
                        <td><?php echo $_POST['deliveryAddress_taxOffice']; ?></td>
                    </tr>
                    <tr>
                        <td>TCKN:</td>
                        <td><?php echo $_POST['deliveryAddress_tckn']; ?></td>
                    </tr>
                    <tr>
                        <td>Telefon Numarasi</td>
                        <td><?php echo $_POST['deliveryAddress_telephone']; ?></td>
                    </tr>
                </table>
            </div>

            <div>
                <h4>Fatura Adresi Bilgisi</h4>
                <table>
                    <tr>
                        <td>Adres Tipi:</td>
                        <td><?php echo $_POST['billingAddress_addressType']; ?></td>
                    </tr>
                    <tr>
                        <td>Il :</td>
                        <td><?php echo $_POST['billingAddress_city']; ?></td>
                    </tr>
                    <tr>
                        <td>Ilce:</td>
                        <td><?php echo $_POST['billingAddress_county']; ?></td>
                    </tr>
                    <tr>
                        <td>Acik Adres :</td>
                        <td><?php echo $_POST['billingAddress_address']; ?></td>
                    </tr>
                    <tr>
                        <td>Firma Unvani :</td>
                        <td><?php echo $_POST['billingAddress_companyName']; ?></td>
                    </tr>
                    <tr>
                        <td>Email :</td>
                        <td><?php echo $_POST['billingAddress_email']; ?></td>
                    </tr>
                    <tr>
                        <td>Ad:</td>
                        <td><?php echo $_POST['billingAddress_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Soyad:</td>
                        <td><?php echo $_POST['billingAddress_surname']; ?></td>
                    </tr>
                    <tr>
                        <td>Vergi Kimlik Numarasi :</td>
                        <td><?php echo $_POST['billingAddress_taxNumber']; ?></td>
                    </tr>
                    <tr>
                        <td>Vergi Dairesi</td>
                        <td><?php echo $_POST['billingAddress_taxOffice']; ?></td>
                    </tr>
                    <tr>
                        <td>TCKN:</td>
                        <td><?php echo $_POST['billingAddress_tckn']; ?></td>
                    </tr>
                    <tr>
                        <td>Telefon Numarasi</td>
                        <td><?php echo $_POST['billingAddress_telephone']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div style="width:100%;position: fixed;bottom: 0;">
        <input onclick='acceptAgreement()' style="width:1%" id="acceptCheckMerchant" name="acceptCheck"
               type="checkbox"/>
        <label for="acceptCheckMerchant" style="text-decoration: underline;color: lightskyblue;cursor: pointer">
            <small>Kullanici sozlesmesini okudum, anladim, kabul ediyorum.</small>
        </label>
    </div>
</div>
</body>

</html>
