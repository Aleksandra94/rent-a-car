# rent-a-car
REST API application for managin and reserving cars from a fictive rent a car company

Za potrebe ovog projekta korišten je XAMPP i MYSQL DATABASE.<br />
Skripte za pravljenje i popunjavanje baze se nalaze na Bitbucketu.<br />
Za pokretanje komande svakog ponedeljka u 9 neophodno je ili kreirati CronJob za Linux ili podesiti TaskScheduler za Windows.<br />
Za testni inbox prilikom slanja e-mailova je korišten mailtrap.<br />
Za testiranje API-a korišten je Postman.<br />
Primjer poziva za listu auta: http://localhost:8000/api/car<br />
Primjer poziva za kreiranje auta:http://localhost:8000/api/car<br />
Primjer body-a:<br />
{<br />
    "registration_licence": "434t4",<br />
    "brand": "Audi",<br />
    "model": "A3",<br />
    "manufacture_date": "2016-03-05",<br />
    "car_description": "Black",<br />
    "properties": "NumOfSeats:5, NumOfDoors:4, Fuel: Dizel",<br />
    "category_id": 6<br />
}<br />
Primjer poziva za update auta: http://localhost:8000/api/car/1 (gdje je 1 id auta)<br />
Primjer poziva za brisanje auta:http://localhost:8000/api/car/1<br />
Za category: //localhost:8000/api/category<br />
	     //localhost:8000/api/category/1<br />
Slug je u obliku: registration_licence-brand-model<br />
Za pretraživanje: http://localhost:8000/api/search?brand=Audi&price=100-140&include-all=true gdje rezultat mora da ispuni sve kriterijume pretrage<br />
http://localhost:8000/api/search?brand=Opel&model=A150&price=90-100&include-all=false  odakle dobijamo sve rezultate koji ispunjavaju barem jedan uslov pretrage<br />
Za rezervaciju auta: http://localhost:8000/api/reservation (registracija se vrši ili na osnovu registration_licence ili na osnovu categorije.<br />
Primjer body-a:<br />
{<br />
    "category": "premium",<br />
    "date_range": "2021-02-05:2021-02-10"<br />
}<br />
Primjer linka za konvertovanje valuta: http://localhost:8000/api/category?currency=BAM gdje umjesto BAM moze da stoji EUR, GBP, itd<br />
Komanda za ekportovanje podataka o rezervacijama u trenutnoj sedmici je php artisan reserved:cars<br />