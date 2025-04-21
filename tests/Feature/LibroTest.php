<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LibroTest extends TestCase
{
    /**
     * A basic feature test example.
     */
        /** @test */
    public function agregar_libro_valido()
    {
        $response = $this->post('/libros', [
            'isbn' => '1234567890',
            'titulo' => 'Cien años de soledad',
            'autor' => 'Gabriel García Marquez',
            'anio' => 1967,
        ]);
        $response->assertStatus(302); // Esperamos redirección
        $this->assertCount(1, Libro::all());
    }

    /** @test */
    public function libro_con_campos_vacios()
    {
        $response = $this->post('/libros', [
            'isbn' => '',
            'titulo' => '',
            'autor' => '',
            'anio' => '',
        ]);

        $response->assertSessionHasErrors(['titulo', 'autor', 'isbn', 'anio']);
    }

    /** @test */
    public function libro_con_isbn_duplicado()
    {
        Libro::create([
            'isbn' => '1111111111',
            'titulo' => 'Original',
            'autor' => 'Autor Uno',
            'anio' => 2000,
        ]);

        $response = $this->post('/libros', [
            'isbn' => '1111111111',
            'titulo' => 'Otro libro',
            'autor' => 'Otro autor',
            'anio_publicacion' => 2024,
        ]);

        $response->assertSessionHasErrors(['isbn']);
    }
}
