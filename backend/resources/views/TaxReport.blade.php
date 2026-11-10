<!DOCTYPE html>
<html>
    <body style="background-color: #FDF2F2; padding-bottom:10px;padding-top:10px">
        <div style="background-color: white; margin-top: 10px;margin-left:auto;margin-right:auto;margin-bottom:10px; padding: 10px 30px 10px 30px; max-width:400px;">
            <p>Dzień dobry,</p>
            </br>
            <p>Informujemy, że zostały przygotowane rozliczenia za {{$date}}</p>
            <p>Do zapłaty pozostają następujące podatki:</p>
            <ul>
                @for ($i = 0; $i < sizeof($taxes); $i++)
                    <li style="display: flex; margin-bottom: 5px;">
                        <p style="width: 50px;">{{$taxes[$i]}}</p>
                        <p> - {{$taxes_v[$i]}} zł, termin płatności: {{$taxes_m[$i]}}</p>
                    </li>
                @endfor
            </ul>
            <p>Płatności należy dokonać na właściwy rachunek podatkowy. </p>
            <p>Prosimy o terminowe uregulowanie należności.</p>
            <p>{{$notes}}</p>
            <br>
            <p>W razie pytań pozostajemy do dyspozycji.</p>
            <br>
            <p>Pozdrawiamy,</p>
            <p>Zespół Księgowy</p>
        </div>
    </body>
</html>