<?php

namespace Tests\Feature;

use App\Models\Alumno;
use App\Models\ConceptoPago;
use App\Models\Grado;
use App\Models\Materia;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\Personal;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CrmSmokeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_pages_render_with_safe_modal_payloads(): void
    {
        $admin = $this->adminUser();
        $grado = Grado::query()->first();

        $pages = [
            'dashboard' => [route('dashboard'), ['topbar', 'page-body', 'padding-top: var(--topbar-h)']],
            'grados' => [route('grados.index'), ['data-grado', 'modal-grado', 'modal-grado-detalle']],
            'materias' => [route('materias.index'), ['data-materia', 'modal-mat', 'modal-mat-detalle']],
            'alumnos' => [route('alumnos.index'), ['data-alumno', 'modal-alumno', 'modal-alumno-detalle']],
            'matriculas' => [route('matriculas.index'), ['data-matricula', 'modal-matricula', 'modal-matricula-detalle']],
            'notas' => [route('notas.index'), ['modal-boleta']],
            'pagos' => [route('pagos.index'), ['data-pago', 'modal-pago', 'modal-pago-detalle']],
            'personal' => [route('personal.index'), ['data-personal', 'modal-personal', 'modal-personal-detalle']],
            'mensajes' => [route('mensajes.index'), ['modal-mensaje', 'modal-mensaje-detalle']],
            'conceptos' => [route('conceptos.index'), ['data-concepto', 'modal-concepto', 'modal-concepto-detalle']],
            'reportes' => [route('reportes.index'), ['Reportes']],
            'configuracion' => [route('configuracion.index'), ['Guardar Cambios']],
            'sistema' => [route('sistema.index'), ['data-sistema-confirm', 'modal-sistema-confirm']],
        ];

        if ($grado) {
            $pages['secciones'] = [route('grados.secciones', $grado), ['data-seccion', 'modal-sec', 'modal-sec-detalle']];
        }

        foreach ($pages as $name => [$url, $needles]) {
            $response = $this->actingAs($admin)->get($url);
            $response->assertOk();
            $html = $response->getContent();

            $this->assertStringNotContainsString("JSON.parse('{", $html, "Inline JSON roto en {$name}");
            foreach ($needles as $needle) {
                $this->assertStringContainsString($needle, $html, "Falta {$needle} en {$name}");
            }
        }
    }

    public function test_core_crud_workflow_uses_current_forms_and_rolls_back(): void
    {
        $admin = $this->adminUser();
        $stamp = now()->format('Hisv');
        $anio = 2026;

        $this->actingAs($admin);

        $this->post(route('grados.store'), [
            'nombre' => "QA Grado {$stamp}",
            'nivel' => 'primaria',
        ])->assertRedirect();
        $grado = Grado::query()->where('nombre', "QA Grado {$stamp}")->firstOrFail();

        $this->put(route('grados.update', $grado), [
            'nombre' => "QA Grado Editado {$stamp}",
            'nivel' => 'primaria',
        ])->assertRedirect();
        $this->assertDatabaseHas('grados', ['id' => $grado->id, 'nombre' => "QA Grado Editado {$stamp}"]);

        $this->post(route('grados.secciones.store', $grado), [
            'nombre' => 'QA',
            'turno' => 'tarde',
            'capacidad' => 25,
        ])->assertRedirect();
        $seccion = Seccion::query()->where('grado_id', $grado->id)->where('nombre', 'QA')->firstOrFail();

        $this->post(route('materias.store'), [
            'nombre' => "QA Materia {$stamp}",
            'codigo' => "QAM{$stamp}",
            'nivel' => 'primaria',
            'horas_semanales' => 4,
            'color' => '#4f86bd',
        ])->assertRedirect();
        $materia = Materia::query()->where('codigo', "QAM{$stamp}")->firstOrFail();

        $dniAlumno = '83' . substr($stamp, -6);
        $this->post(route('alumnos.store'), [
            'dni' => $dniAlumno,
            'nombres' => 'QA Alumno',
            'apellidos' => "Smoke {$stamp}",
            'fecha_nacimiento' => '2017-04-12',
            'genero' => 'M',
            'direccion' => 'Av. Prueba 123',
            'telefono' => '999111222',
            'email' => "qa.alumno.{$stamp}@colegio.test",
            'apoderado_nombre' => 'QA Apoderado',
            'apoderado_parentesco' => 'Padre',
        ])->assertRedirect();
        $alumno = Alumno::query()->where('dni', $dniAlumno)->firstOrFail();

        $this->post(route('matriculas.store'), [
            'alumno_id' => $alumno->id,
            'grado_id' => $grado->id,
            'seccion_id' => $seccion->id,
            'anio_escolar' => $anio,
            'fecha_matricula' => '2026-03-01',
            'observaciones' => 'Smoke inicial',
        ])->assertRedirect();
        $this->assertDatabaseHas('matriculas', [
            'alumno_id' => $alumno->id,
            'seccion_id' => $seccion->id,
            'anio_escolar' => $anio,
        ]);

        $this->post(route('notas.guardar'), [
            'seccion_id' => $seccion->id,
            'materia_id' => $materia->id,
            'anio_escolar' => $anio,
            'bimestre' => 1,
            'notas' => [$alumno->id => 17],
        ])->assertRedirect();
        $this->assertDatabaseHas('notas', [
            'alumno_id' => $alumno->id,
            'materia_id' => $materia->id,
            'seccion_id' => $seccion->id,
            'anio_escolar' => $anio,
            'bimestre' => 1,
        ]);

        $this->post(route('conceptos.store'), [
            'nombre' => "QA Concepto {$stamp}",
            'descripcion' => 'Concepto smoke',
            'monto' => 120,
            'tipo' => 'otros',
            'activo' => '1',
        ])->assertRedirect();
        $concepto = ConceptoPago::query()->where('nombre', "QA Concepto {$stamp}")->firstOrFail();

        $this->post(route('pagos.store'), [
            'alumno_id' => $alumno->id,
            'concepto_id' => $concepto->id,
            'anio_escolar' => $anio,
            'mes' => 3,
            'monto' => 120,
            'descuento' => 0,
            'monto_pagado' => 120,
            'fecha_pago' => '2026-03-05',
            'fecha_vencimiento' => '2026-03-30',
            'metodo_pago' => 'efectivo',
            'estado' => 'pagado',
            'observaciones' => 'Smoke pago',
        ])->assertRedirect();
        $this->assertTrue(Pago::query()->where('alumno_id', $alumno->id)->where('concepto_id', $concepto->id)->exists());

        $dniPersonal = '93' . substr($stamp, -6);
        $this->post(route('personal.store'), [
            'dni' => $dniPersonal,
            'nombres' => 'QA Personal',
            'apellidos' => "Smoke {$stamp}",
            'tipo' => 'docente',
            'especialidad' => 'Primaria',
            'telefono' => '988777666',
            'email' => "qa.personal.{$stamp}@colegio.test",
            'direccion' => 'Jr. Docente 100',
            'fecha_ingreso' => '2026-01-10',
            'salario' => 2500,
            'estado' => 'activo',
        ])->assertRedirect();
        $this->assertTrue(Personal::query()->where('dni', $dniPersonal)->exists());

        $recipient = User::query()->where('id', '!=', $admin->id)->firstOrFail();
        $this->post(route('mensajes.store'), [
            'destinatario_id' => $recipient->id,
            'asunto' => "QA Mensaje {$stamp}",
            'cuerpo' => 'Mensaje smoke',
        ])->assertRedirect();
        $this->assertDatabaseHas('mensajes', [
            'remitente_id' => $admin->id,
            'destinatario_id' => $recipient->id,
            'asunto' => "QA Mensaje {$stamp}",
        ]);
    }

    private function adminUser(): User
    {
        return User::query()->where('role', 'admin')->first()
            ?? User::query()->create([
                'name' => 'QA Admin',
                'email' => 'qa-admin-'.uniqid().'@colegio.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'activo' => true,
            ]);
    }
}
