<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcesarImagen
{
    protected $imageFile;
    protected $folder;
    protected $fileName;
    protected $width;
    protected $height;
    protected $watermarkPath;
    protected $watermarkPosition = 'center';
    protected $opacity = 50;
    protected $formato;
    protected $copiaOriginal;
    protected $marginX;
    protected $marginY;
    public function formato(string $formato)
    {
        $formato = strtolower($formato);
        if (!in_array($formato, ['jpg', 'jpeg', 'png'])) {
            throw new \Exception("El formato especificado no es válido. Debe ser 'jpg' o 'png'.");
        }
        $this->formato = $formato;
        return $this;
    }
    public static function crear(UploadedFile $imageFile)
    {
        if (!$imageFile instanceof UploadedFile) {
            throw new \Exception("El archivo proporcionado no es válido.");
        }
        if (!$imageFile->isValid()) {
            throw new \Exception("El archivo de imagen no es válido o está corrupto.");
        }
        $instance = new self();
        $instance->imageFile = $imageFile;
        return $instance;
    }

    public function carpeta(string $folder)
    {
        if (empty($folder)) {
            throw new \Exception("La carpeta no puede estar vacía.");
        }
        $this->folder = $folder;
        return $this;
    }

    public function nombreArchivo(string $fileName)
    {
        if (!empty($fileName)) {
            if (preg_match('/^[a-zA-Z0-9_\-]+\.[a-zA-Z0-9]+$/', $fileName)) {
                throw new \Exception("El nombre del archivo contiene caracteres no permitidos o falta la extensión.");
            }
            if (pathinfo($fileName, PATHINFO_EXTENSION)) {
                throw new \Exception("El nombre del archivo no debe contener una extensión si se especifica un formato.");
            }
        }
        $this->fileName = $fileName;
        return $this;
    }

    public function dimensiones(?int $width = null, ?int $height = null)
    {
        if (($width !== null && $width <= 0) || ($height !== null && $height <= 0)) {
            throw new \Exception("Las dimensiones deben ser números positivos.");
        }
        $this->width = $width;
        $this->height = $height;
        return $this;
    }
    public function conCopiaOriginal(string $carpeta)
    {
        // Validar que se proporcione un nombre de carpeta
        if (empty($carpeta)) {
            throw new \Exception("Se debe proporcionar un nombre de carpeta para la copia original.");
        }

        // Validar que la carpeta de copia original no sea la misma que la carpeta de destino principal
        if ($carpeta === $this->folder) {
            throw new \Exception("La carpeta de la copia original no puede ser la misma que la carpeta de destino principal.");
        }
        $this->copiaOriginal = $carpeta;
        return $this;
    }
    public function marcaDeAgua(string $watermarkPath, string $position = 'center', int $opacity = 50, $marginX = 0, $marginY = 0)
    {
        if (empty($watermarkPath) || !file_exists(public_path($watermarkPath))) {
            throw new \Exception("La ruta de la marca de agua no es válida o el archivo no existe.");
        }
        $validPositions = ['center', 'top', 'bottom', 'left', 'right', 'top-left', 'top-right', 'bottom-left', 'bottom-right'];
        if (!in_array($position, $validPositions)) {
            throw new \Exception("La posición de la marca de agua no es válida.");
        }
        if ($opacity < 0 || $opacity > 100) {
            throw new \Exception("La opacidad debe estar entre 0 y 100.");
        }
        $this->watermarkPath = $watermarkPath;
        $this->marginX = $marginX;
        $this->marginY = $marginY;
        $this->watermarkPosition = $position;
        $this->opacity = $opacity;
        return $this;
    }

    public function guardar()
    {
        if (empty($this->folder)) {
            throw new \Exception("Debe especificar una carpeta antes de guardar.");
        }
        if (empty($this->fileName)) {
            if (isset($this->formato)) {
                $this->fileName = uniqid() . '.' . $this->formato;
            } else {
                $this->fileName = uniqid() . '.' . $this->imageFile->getClientOriginalExtension();
            }
        } else {
            if (isset($this->formato)) {
                $this->fileName = $this->fileName . '.' . $this->formato;
            } else {
                $this->fileName = $this->fileName . '.' . $this->imageFile->getClientOriginalExtension();
            }
        }

        // Guardar la copia original si se ha especificado
        if ($this->copiaOriginal) {
            $disk = GlobalHelper::discoArchivos();
            $this->imageFile->storeAs($this->copiaOriginal, $this->fileName, ['disk' => $disk, 'visibility' => 'public']);
        }
        $temporaryPath = $this->imageFile->getRealPath();
        $image = Image::make($temporaryPath);
        if (isset($this->formato)) {
            $image->encode($this->formato);
        }
        if ($this->width || $this->height) {
            $image->resize($this->width, $this->height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $image->orientate();
        if ($this->watermarkPath) {
            $marginY = $this->marginY;
            $marginX = $this->marginX;
            $watermark = Image::make(public_path($this->watermarkPath));
            $watermarkWidth = $image->width() * 0.3; // 30% del ancho de la imagen principal
            $watermark->resize($watermarkWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $watermark->opacity($this->opacity);
            // Insertar la marca de agua en la posición calculada
            $image->insert($watermark, $this->watermarkPosition, $marginX, $marginY);
        }

        $disk = GlobalHelper::discoArchivos();
        $path = "{$this->folder}/{$this->fileName}";

        $respuesta = Storage::disk($disk)->put($path, $image->encode(), 'public');
        if ($respuesta == false) {
            throw new \Exception("Ocurrio un error al subir la imagen: " . $respuesta);
        }
        // dd($respuesta);
        return $this->fileName;
    }
}
