<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidarTelefonoPorPais implements Rule
{
    protected $codigoPais;
    protected $mensajeError;

    /**
     * Configuración de países con sus códigos y longitudes válidas
     */
    protected $paises = [
        '+591' => ['nombre' => 'Bolivia', 'digitos' => 8],
        '+54' => ['nombre' => 'Argentina', 'digitos' => 10],
        '+55' => ['nombre' => 'Brasil', 'digitos' => 11],
        '+56' => ['nombre' => 'Chile', 'digitos' => 9],
        '+57' => ['nombre' => 'Colombia', 'digitos' => 10],
        '+593' => ['nombre' => 'Ecuador', 'digitos' => 9],
        '+51' => ['nombre' => 'Perú', 'digitos' => 9],
        '+595' => ['nombre' => 'Paraguay', 'digitos' => 9],
        '+598' => ['nombre' => 'Uruguay', 'digitos' => 8],
        '+58' => ['nombre' => 'Venezuela', 'digitos' => 10],
        '+52' => ['nombre' => 'México', 'digitos' => 10],
        '+1' => ['nombre' => 'USA/Canadá', 'digitos' => 10],
        '+34' => ['nombre' => 'España', 'digitos' => 9],
    ];

    /**
     * Create a new rule instance.
     *
     * @param string $codigoPais
     * @return void
     */
    public function __construct($codigoPais)
    {
        $this->codigoPais = $codigoPais;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Verificar que el código de país exista en nuestra configuración
        if (!isset($this->paises[$this->codigoPais])) {
            $this->mensajeError = 'El código de país seleccionado no es válido.';
            return false;
        }

        $config = $this->paises[$this->codigoPais];

        // Limpiar el valor (remover espacios, guiones, etc.)
        $telefonoLimpio = preg_replace('/[^0-9]/', '', $value);

        // Verificar que solo contenga números
        if (!preg_match('/^\d+$/', $telefonoLimpio)) {
            $this->mensajeError = 'El teléfono debe contener solo números.';
            return false;
        }

        // Verificar la longitud según el país
        if (strlen($telefonoLimpio) !== $config['digitos']) {
            $this->mensajeError = "El teléfono de {$config['nombre']} debe tener exactamente {$config['digitos']} dígitos.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->mensajeError ?? 'El número de teléfono no es válido para el país seleccionado.';
    }

    /**
     * Obtener la lista de países disponibles
     *
     * @return array
     */
    public static function getPaisesDisponibles()
    {
        return [
            '+591' => ['nombre' => 'Bolivia', 'digitos' => 8],
            '+54' => ['nombre' => 'Argentina', 'digitos' => 10],
            '+55' => ['nombre' => 'Brasil', 'digitos' => 11],
            '+56' => ['nombre' => 'Chile', 'digitos' => 9],
            '+57' => ['nombre' => 'Colombia', 'digitos' => 10],
            '+593' => ['nombre' => 'Ecuador', 'digitos' => 9],
            '+51' => ['nombre' => 'Perú', 'digitos' => 9],
            '+595' => ['nombre' => 'Paraguay', 'digitos' => 9],
            '+598' => ['nombre' => 'Uruguay', 'digitos' => 8],
            '+58' => ['nombre' => 'Venezuela', 'digitos' => 10],
            '+52' => ['nombre' => 'México', 'digitos' => 10],
            '+1' => ['nombre' => 'USA/Canadá', 'digitos' => 10],
            '+34' => ['nombre' => 'España', 'digitos' => 9],
        ];
    }
}
