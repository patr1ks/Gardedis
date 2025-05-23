# Gardēdis

**Gardēdis** ir tīmekļa platforma, kas izstrādāta kā kvalifikācijas darbs Liepājas Valsts tehnikumā. Tā ļauj lietotājiem atrast, aplūkot un rezervēt galdiņus restorānos visā Latvijā. Restorānu īpašniekiem ir iespēja pārvaldīt savus restorānus, augšupielādēt izkārtojumus un sekot rezervācijām.

## Funkcionalitāte

### Lietotājiem
- Pārlūkot restorānu sarakstu pēc kategorijām vai meklēt
- Skatīt restorānu profilus ar aprakstiem, attēliem un sēdvietu izkārtojumu
- Veikt galdiņu rezervācijas izvēloties datumu, laiku un sēdvietu skaitu
- Apskatīt un pārvaldīt savu rezervāciju vēsturi

### Restorānu īpašniekiem
- Reģistrēt un pārvaldīt restorānu profilus
- Augšupielādēt un rediģēt restorāna attēlus un informāciju
- Veidot un pielāgot restorāna izkārtojumu ar galdiņiem un sienām
- Apskatīt rezervācijas un pārvaldīt tās
- Sekot maksājumu informācijai

### Administratoriem
- Pārvaldīt visus lietotājus un restorānus
- Apskatīt visas rezervācijas un maksājumus platformā
- Apstiprināt vai noraidīt restorānu pieteikumus

## Tehnoloģijas

- **Frontend:** Vue 3, Inertia.js, Tailwind CSS, Element Plus
- **Backend:** Laravel 10, Eloquent ORM
- **Datu bāze:** MySQL
- **Citi rīki:** SweetAlert2, Axios, JSON datu saglabāšanai un nolasīšanai

## Uzstādīšana

```bash
# Instalēt dependencies
npm install

# Kompilēt frontenda resursus
npm run dev

# Migrēt un aizpildīt datubāzi
php artisan migrate --seed

# Startēt Laravel serveri
php artisan serve
