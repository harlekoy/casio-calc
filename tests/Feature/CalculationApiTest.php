<?php

namespace Tests\Feature;

use App\Models\Calculation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculationApiTest extends TestCase
{
    use RefreshDatabase;

    private string $sessionId = '00000000-0000-4000-a000-000000000001';

    private string $otherSessionId = '00000000-0000-4000-a000-000000000002';

    // ─── Store Calculation (POST /api/calculations) ───

    public function test_can_calculate_basic_addition(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '2+3',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['expression' => '2+3', 'result' => '5']);
    }

    public function test_can_calculate_subtraction(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '10-4',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '6']);
    }

    public function test_can_calculate_multiplication(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '6*7',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '42']);
    }

    public function test_can_calculate_division(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '20/4',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '5']);
    }

    public function test_can_calculate_decimal_result(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '10/3',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201);

        $result = (float) $response->json('result');
        $this->assertEqualsWithDelta(3.3333333333, $result, 0.0000001);
    }

    public function test_can_calculate_sqrt(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'sqrt(144)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '12']);
    }

    public function test_can_calculate_exponent(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '2^10',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '1024']);
    }

    public function test_can_calculate_sin(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'sin(0)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '0']);
    }

    public function test_can_calculate_cos(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'cos(0)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '1']);
    }

    public function test_can_calculate_tan(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'tan(0)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '0']);
    }

    public function test_can_calculate_asin(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'asin(1)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201);
        $result = (float) $response->json('result');
        $this->assertEqualsWithDelta(M_PI / 2, $result, 0.0000001);
    }

    public function test_can_calculate_acos(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'acos(1)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '0']);
    }

    public function test_can_calculate_atan(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'atan(0)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '0']);
    }

    public function test_can_calculate_ln(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'ln(e)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '1']);
    }

    public function test_can_calculate_log10(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'log10(100)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '2']);
    }

    public function test_can_calculate_exp(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'exp(0)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '1']);
    }

    public function test_can_calculate_abs(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'abs(-42)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '42']);
    }

    public function test_can_calculate_factorial(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '5!',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '120']);
    }

    public function test_can_calculate_with_pi_constant(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'pi',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201);
        $result = (float) $response->json('result');
        $this->assertEqualsWithDelta(M_PI, $result, 0.0000001);
    }

    public function test_can_calculate_with_e_constant(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'e',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201);
        $result = (float) $response->json('result');
        $this->assertEqualsWithDelta(M_E, $result, 0.0000001);
    }

    public function test_can_calculate_floor(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'floor(3.7)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '3']);
    }

    public function test_can_calculate_ceil(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'ceil(3.2)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '4']);
    }

    public function test_can_calculate_nested_parentheses(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '((2+3)*4)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201)
            ->assertJsonFragment(['result' => '20']);
    }

    public function test_can_calculate_complex_pasted_expression(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'sqrt((((9*9)/12)+(13-4))*2)^2',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(201);
        $this->assertNotNull($response->json('result'));
    }

    public function test_rejects_invalid_expression(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '2++3',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => 'Invalid expression']);
    }

    public function test_rejects_unbalanced_parentheses(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => 'sqrt((((9*9)/12)+(13-4))*2)^2)',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => 'Invalid expression']);
    }

    public function test_rejects_division_by_zero(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '1/0',
        ], ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(422);
    }

    public function test_rejects_missing_expression(): void
    {
        $response = $this->postJson('/api/calculations', [], [
            'X-Session-Id' => $this->sessionId,
        ]);

        $response->assertStatus(422);
    }

    public function test_store_requires_session_id_header(): void
    {
        $response = $this->postJson('/api/calculations', [
            'expression' => '2+3',
        ]);

        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'Session ID required']);
    }

    public function test_calculation_is_persisted_to_database(): void
    {
        $this->postJson('/api/calculations', [
            'expression' => '5+5',
        ], ['X-Session-Id' => $this->sessionId]);

        $this->assertDatabaseHas('calculations', [
            'session_id' => $this->sessionId,
            'expression' => '5+5',
            'result' => '10',
        ]);
    }

    // ─── Index Calculations (GET /api/calculations) ───

    public function test_can_list_calculations_for_session(): void
    {
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '1+1', 'result' => '2']);
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '2+2', 'result' => '4']);

        $response = $this->getJson('/api/calculations', ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_index_returns_only_own_session_calculations(): void
    {
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '1+1', 'result' => '2']);
        Calculation::create(['session_id' => $this->otherSessionId, 'expression' => '3+3', 'result' => '6']);

        $response = $this->getJson('/api/calculations', ['X-Session-Id' => $this->sessionId]);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['expression' => '1+1'])
            ->assertJsonMissing(['expression' => '3+3']);
    }

    public function test_index_requires_session_id_header(): void
    {
        $response = $this->getJson('/api/calculations');

        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'Session ID required']);
    }

    public function test_index_returns_calculations_in_descending_order(): void
    {
        $this->travel(-3)->minutes();
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '1+1', 'result' => '2']);

        $this->travel(1)->minutes();
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '2+2', 'result' => '4']);

        $this->travel(1)->minutes();
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '3+3', 'result' => '6']);

        $this->travelBack();

        $response = $this->getJson('/api/calculations', ['X-Session-Id' => $this->sessionId]);

        $results = $response->json();
        $this->assertEquals('6', $results[0]['result']);
        $this->assertEquals('4', $results[1]['result']);
        $this->assertEquals('2', $results[2]['result']);
    }

    // ─── Destroy Calculation (DELETE /api/calculations/{id}) ───

    public function test_can_delete_own_calculation(): void
    {
        $calculation = Calculation::create(['session_id' => $this->sessionId, 'expression' => '1+1', 'result' => '2']);

        $response = $this->deleteJson("/api/calculations/{$calculation->id}", [], [
            'X-Session-Id' => $this->sessionId,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('calculations', ['id' => $calculation->id]);
    }

    public function test_cannot_delete_another_sessions_calculation(): void
    {
        $calculation = Calculation::create(['session_id' => $this->otherSessionId, 'expression' => '1+1', 'result' => '2']);

        $response = $this->deleteJson("/api/calculations/{$calculation->id}", [], [
            'X-Session-Id' => $this->sessionId,
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseHas('calculations', ['id' => $calculation->id]);
    }

    // ─── Destroy All Calculations (DELETE /api/calculations) ───

    public function test_can_clear_all_own_calculations(): void
    {
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '1+1', 'result' => '2']);
        Calculation::create(['session_id' => $this->sessionId, 'expression' => '2+2', 'result' => '4']);
        Calculation::create(['session_id' => $this->otherSessionId, 'expression' => '3+3', 'result' => '6']);

        $response = $this->deleteJson('/api/calculations', [], [
            'X-Session-Id' => $this->sessionId,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseCount('calculations', 1);
        $this->assertDatabaseHas('calculations', ['session_id' => $this->otherSessionId]);
    }

    public function test_clear_all_requires_session_id_header(): void
    {
        $response = $this->deleteJson('/api/calculations');

        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'Session ID required']);
    }

    // ─── Session Isolation ───

    public function test_different_sessions_have_isolated_data(): void
    {
        $this->postJson('/api/calculations', ['expression' => '1+1'], ['X-Session-Id' => $this->sessionId]);
        $this->postJson('/api/calculations', ['expression' => '2+2'], ['X-Session-Id' => $this->otherSessionId]);

        $response1 = $this->getJson('/api/calculations', ['X-Session-Id' => $this->sessionId]);
        $response2 = $this->getJson('/api/calculations', ['X-Session-Id' => $this->otherSessionId]);

        $response1->assertJsonCount(1)->assertJsonFragment(['expression' => '1+1']);
        $response2->assertJsonCount(1)->assertJsonFragment(['expression' => '2+2']);
    }
}
