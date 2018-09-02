<?php

namespace App\Providers;

use Faker\Provider\Base;

class FakerServiceProvider extends Base
{
    public $animal_names = ['Alecrim', 'Alex', 'Alice', 'Amarelinho', 'Ambar', 'Amendoim', 'Archie', 'Arcol', 'Artemisa', 'Ary', 'Arya', 'Atena', 'Aurora', 'Babalu', 'Barbudo', 'Bea', 'Biscoito', 'Chico', 'Cinza', 'Codi', 'Cookie', 'Denver', 'Dona Xica', 'Elefante', 'Estrela', 'Felicia', 'Flor', 'Florbela', 'Francisca', 'Fuji', 'Gaspar', 'Gaya', 'Gil', 'Jade', 'Jecky', 'Johnny', 'Joy', 'Kat', 'Kedi', 'Kid', 'Kit', 'Kyra', 'Leggy', 'Leica', 'Lia', 'Luana', 'Luca', 'Lucky', 'Luna', 'Mamy', 'Matias', 'Melba', 'Miona', 'Neruda', 'Nikita', 'Oliver', 'Oscar', 'Pakora', 'Panti', 'Pança', 'Ping', 'Pirata', 'Plaza', 'Pong', 'Porto', 'Queen', 'Ravi', 'Robin', 'Rufi', 'Sancho', 'Simba', 'Sophie', 'Tareka', 'Tita', 'Trevo', 'Tricolor', 'Vasco', 'Yoko', 'Zenit', 'Óscar'];

    public function animal()
    {
        return $this->animal_names[rand(0, count($this->animal_names) - 1)];
    }
}
