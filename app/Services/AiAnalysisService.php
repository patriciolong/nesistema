<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAnalysisService
{
    /**
     * Generate an AI executive diagnosis and strategic audit report.
     */
    public function generateExecutiveReport(array $analytics): array
    {
        $kpis = $analytics['kpis'] ?? [];
        $usuarios = $analytics['usuarios_rendimiento'] ?? [];
        $tramites = $analytics['tramites_categorias'] ?? [];
        $metodos = $analytics['metodos_pago_montos'] ?? [];
        $metodosCounts = $analytics['metodos_pago_counts'] ?? [];
        $oficinas = $analytics['oficinas_rendimiento'] ?? [];
        $horaPico = $kpis['hora_pico'] ?? 'Horario laboral regular';

        // Check if Gemini or OpenAI API Key is configured
        $geminiKey = config('services.gemini.key');
        $openaiKey = config('services.openai.key');

        if (!empty($geminiKey)) {
            $apiResult = $this->callGeminiApi($analytics, 'Genera un diagnóstico ejecutivo integral con resumen, logros estratégicos, riesgos operativos y 4 recomendaciones prioritarias en formato JSON.');
            if ($apiResult) {
                return $apiResult;
            }
        } elseif (!empty($openaiKey)) {
            $apiResult = $this->callOpenAiApi($analytics, 'Genera un diagnóstico ejecutivo integral con resumen, logros estratégicos, riesgos operativos y 4 recomendaciones prioritarias en formato JSON.');
            if ($apiResult) {
                return $apiResult;
            }
        }

        // Advanced Statistical & Heuristic Local AI Generation Engine
        return $this->generateLocalAiReport($analytics);
    }

    /**
     * Answer a specific natural language query about the system analytics.
     */
    public function answerQuestion(string $question, array $analytics): array
    {
        $geminiKey = config('services.gemini.key');
        $openaiKey = config('services.openai.key');

        if (!empty($geminiKey)) {
            $answer = $this->callGeminiForQuestion($question, $analytics);
            if ($answer) {
                return [
                    'success' => true,
                    'question' => $question,
                    'answer' => $answer,
                    'engine' => 'Gemini AI Cloud Engine',
                ];
            }
        } elseif (!empty($openaiKey)) {
            $answer = $this->callOpenAiForQuestion($question, $analytics);
            if ($answer) {
                return [
                    'success' => true,
                    'question' => $question,
                    'answer' => $answer,
                    'engine' => 'OpenAI GPT Cloud Engine',
                ];
            }
        }

        // Local semantic matching and contextual AI synthesis
        $answer = $this->generateLocalAiAnswer($question, $analytics);
        return [
            'success' => true,
            'question' => $question,
            'answer' => $answer,
            'engine' => 'Motor de IA Analítica NESISTEMA 2.0',
        ];
    }

    /**
     * Local Heuristic AI Analysis generator with high contextual depth.
     */
    private function generateLocalAiReport(array $analytics): array
    {
        $kpis = $analytics['kpis'] ?? [];
        $usuarios = $analytics['usuarios_rendimiento'] ?? [];
        $tramites = $analytics['tramites_categorias'] ?? [];
        $metodos = $analytics['metodos_pago_montos'] ?? [];
        $metodosCounts = $analytics['metodos_pago_counts'] ?? [];
        $oficinas = $analytics['oficinas_rendimiento'] ?? [];
        $topMotivos = $analytics['top_motivos'] ?? [];

        $totalRecaudado = number_format($kpis['total_recaudado'] ?? 0, 2);
        $totalTramites = $kpis['total_tramites'] ?? 0;
        $cuadreRate = $kpis['porcentaje_cuadre'] ?? 100;
        $topUser = $kpis['top_usuario'] ?? 'N/A';
        $topOficina = $kpis['top_oficina'] ?? 'Sede Principal';
        $horaPico = $kpis['hora_pico'] ?? '11:00 - 14:00';

        // Find primary trámite category
        $primaryTramite = !empty($tramites) ? $tramites[0]['categoria'] : 'Poderes Notariales';
        $primaryTramitePct = !empty($tramites) ? $tramites[0]['porcentaje'] : 0;

        // Payment cash vs digital ratio
        $totalMonto = array_sum($metodos) ?: 1;
        $efectivoMonto = $metodos['Efectivo'] ?? 0;
        $efectivoPct = round(($efectivoMonto / $totalMonto) * 100, 1);
        $digitalPct = round(100 - $efectivoPct, 1);

        // Build Highlights
        $highlights = [
            "**Liderazgo de Trámites:** El servicio con mayor tracción operativa es **{$primaryTramite}** representando el **{$primaryTramitePct}%** del volumen total del sistema.",
            "**Rendimiento del Personal:** El usuario con mayor índice de productividad y recaudación es **{$topUser}**, manteniendo una consistencia alta en procesamiento de casos.",
            "**Concentración de Sucursales:** La sede **{$topOficina}** se consolida como el principal polo de operaciones del negocio.",
            "**Precisión en Cajas:** Se registra una tasa de cuadre de cierres de caja del **{$cuadreRate}%**, indicando un control de arqueo sólido.",
        ];

        // Build Bottlenecks / Risks
        $bottlenecks = [];
        if ($efectivoPct > 50) {
            $bottlenecks[] = "**Alto Flujo de Efectivo Físico ({$efectivoPct}%):** Existe una fuerte dependencia de cobros en efectivo frente a transferencias y tarjeta ({$digitalPct}%), lo cual incrementa el riesgo de custodia y tiempo en arqueos.";
        } else {
            $bottlenecks[] = "**Adopción de Métodos Digitales ({$digitalPct}%):** Buen equilibrio en medios de pago, se sugiere mantener tarifas de comisión POS optimizadas.";
        }

        if (($kpis['cajas_descuadradas'] ?? 0) > 0) {
            $bottlenecks[] = "**Descuadres Registrados:** Se detectaron **{$kpis['cajas_descuadradas']} sesiones de caja** con diferencias en el arqueo final que requieren revisión del supervisor.";
        }

        if (count($oficinas) > 1 && isset($oficinas[1])) {
            $diffPct = round($oficinas[0]['porcentaje'] - $oficinas[1]['porcentaje'], 1);
            if ($diffPct > 40) {
                $bottlenecks[] = "**Desbalance Inter-Sucursales:** La sucursal **{$oficinas[1]['nombre']}** registra un volumen significativamente menor que **{$oficinas[0]['nombre']}** (brecha del {$diffPct}%).";
            }
        }

        // Actionable Recommendations
        $recommendations = [
            [
                'prioridad' => 'Alta',
                'color' => '#ef4444',
                'titulo' => 'Optimización del Turno en Horario Pico',
                'descripcion' => "La mayor afluencia de inicios de sesión y operaciones se concentra en **{$horaPico}**. Se recomienda reforzar la atención en ventanilla y cajas durante esa franja para reducir tiempos de espera.",
            ],
            [
                'prioridad' => 'Alta',
                'color' => '#ef4444',
                'titulo' => 'Incentivo a Métodos de Pago Electrónicos',
                'descripcion' => "Fomentar el cobro vía transferencia bancaria y tarjetas para agilizar el flujo de caja, reducir el conteo físico de billetes y minimizar riesgos de custodia.",
            ],
            [
                'prioridad' => 'Media',
                'color' => '#f59e0b',
                'titulo' => 'Estrategia de Expansión para Servicios Secundarios',
                'descripcion' => "Impulsar los trámites de menor volumen (como Divorcios, Impuestos y Trámites Personalizados) mediante campañas dirigidas a la cartera de clientes existentes.",
            ],
            [
                'prioridad' => 'Informativa',
                'color' => '#3b82f6',
                'titulo' => 'Estandarización de Plantillas Notariales',
                'descripcion' => "Promover el uso del Diseñador y Generador de Documentos para reducir en más de un 60% el tiempo de redacción manual en trámites de Poderes y Certificaciones.",
            ],
        ];

        return [
            'executive_summary' => "El sistema registra un movimiento financiero consolidado de **\${$totalRecaudado} USD** distribuidos en **{$totalTramites} trámites** procesados. La operación se encuentra liderada por la sucursal **{$topOficina}** y traccionada principalmente por el área de **{$primaryTramite}**. La disciplina de cajas se mantiene con un **{$cuadreRate}% de efectividad**, destacando a **{$topUser}** como el colaborador con mayor impacto productivo del periodo evaluado.",
            'strategic_highlights' => $highlights,
            'operational_bottlenecks' => $bottlenecks,
            'actionable_recommendations' => $recommendations,
            'engine' => 'Motor de IA Analítica & Auditoría NESISTEMA',
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * Local AI dynamic Q&A responder.
     */
    private function generateLocalAiAnswer(string $question, array $analytics): string
    {
        $q = mb_strtolower(trim($question));
        $kpis = $analytics['kpis'] ?? [];
        $usuarios = $analytics['usuarios_rendimiento'] ?? [];
        $tramites = $analytics['tramites_categorias'] ?? [];
        $metodos = $analytics['metodos_pago_montos'] ?? [];
        $metodosCounts = $analytics['metodos_pago_counts'] ?? [];
        $oficinas = $analytics['oficinas_rendimiento'] ?? [];

        if (str_contains($q, 'usuario') || str_contains($q, 'empleado') || str_contains($q, 'cajero') || str_contains($q, 'rendimiento') || str_contains($q, 'quien')) {
            $top = !empty($usuarios) ? $usuarios[0] : null;
            if ($top) {
                return "### 🏆 Análisis de Rendimiento de Usuarios\n\n" .
                    "El colaborador con mayor desempeño global es **{$top['name']}** ({$top['role']}), destacándose con los siguientes indicadores:\n\n" .
                    "- **Total de Trámites Procesados:** {$top['total_tramites']}\n" .
                    "- **Recaudación Directa:** \$" . number_format($top['total_recaudado'], 2) . " USD\n" .
                    "- **Sesiones de Caja:** {$top['sesiones_caja']} (Efectividad de cuadre: {$top['efectividad_caja']}%)\n" .
                    "- **Puntaje de Eficiencia IA:** {$top['score']} / 100\n\n" .
                    "> **Recomendación IA:** Reconocer el liderazgo operativo de {$top['name']} y utilizar sus flujos de trabajo como estándar de capacitación para nuevos empleados.";
            }
        }

        if (str_contains($q, 'venta') || str_contains($q, 'ingreso') || str_contains($q, 'dinero') || str_contains($q, 'recaudado') || str_contains($q, 'cuanto')) {
            $total = number_format($kpis['total_recaudado'] ?? 0, 2);
            $ticket = number_format($kpis['ticket_promedio'] ?? 0, 2);
            $crecimiento = $kpis['crecimiento_recaudacion'] ?? 0;
            return "### 💰 Análisis de Facturación y Ventas\n\n" .
                "Métricas financieras consolidadas del periodo:\n\n" .
                "- **Ingreso Total Recaudado:** \${$total} USD\n" .
                "- **Ticket Promedio por Pago:** \${$ticket} USD\n" .
                "- **Variación de Crecimiento:** {$crecimiento}%\n\n" .
                "El flujo de ingresos muestra estabilidad con una recaudación diaria promedio constante.";
        }

        if (str_contains($q, 'tramite') || str_contains($q, 'poder') || str_contains($q, 'servicio') || str_contains($q, 'popular')) {
            $topT = !empty($tramites) ? $tramites[0] : null;
            $items = "";
            foreach (array_slice($tramites, 0, 4) as $t) {
                $items .= "- **{$t['categoria']}:** {$t['cantidad']} trámites ({$t['porcentaje']}%)\n";
            }
            return "### 📑 Desglose de Trámites más Demandados\n\n" .
                "La distribución de trámites en el sistema está encabezada por **{$topT['categoria']}** con un {$topT['porcentaje']}% de participación.\n\n" .
                $items . "\n" .
                "> **Oportunidad Detectada:** Existe alta demanda notarial; se recomienda habilitar plantillas automáticas adicionales para acelerar tiempos de entrega.";
        }

        if (str_contains($q, 'oficina') || str_contains($q, 'sucursal') || str_contains($q, 'brooklyn') || str_contains($q, 'spring')) {
            $topO = !empty($oficinas) ? $oficinas[0] : null;
            $ofiDetails = "";
            foreach ($oficinas as $ofi) {
                $ofiDetails .= "- **{$ofi['nombre']}:** {$ofi['total_tramites']} trámites | \$" . number_format($ofi['total_recaudado'], 2) . " USD recaudados ({$ofi['porcentaje']}% del movimiento general)\n";
            }
            return "### 🏢 Comparativa de Sedes y Oficinas\n\n" .
                "La oficina que más movimiento registra es **{$topO['nombre']}**.\n\n" .
                $ofiDetails . "\n" .
                "> **Acción sugerida:** Implementar incentivos o campañas de captación en sedes secundarias para equilibrar la carga operativa.";
        }

        if (str_contains($q, 'hora') || str_contains($q, 'sesion') || str_contains($q, 'login') || str_contains($q, 'horario')) {
            return "### ⏰ Análisis de Inicios de Sesión y Concurrencia\n\n" .
                "La hora de mayor actividad registrada en el sistema es **{$kpis['hora_pico']}**.\n\n" .
                "- El personal inicia sus jornadas principalmente en el bloque matutino.\n" .
                "- Se recomienda mantener los mostradores de caja totalmente habilitados entre las **10:00 AM y las 3:00 PM** para evitar filas de espera.";
        }

        if (str_contains($q, 'pago') || str_contains($q, 'tarjeta') || str_contains($q, 'efectivo') || str_contains($q, 'transferencia') || str_contains($q, 'cheque')) {
            $pagoText = "";
            foreach ($metodos as $metodo => $monto) {
                $cnt = $metodosCounts[$metodo] ?? 0;
                $pagoText .= "- **{$metodo}:** \$" . number_format($monto, 2) . " USD ({$cnt} operaciones)\n";
            }
            return "### 💳 Desglose de Métodos de Pago\n\n" .
                "Composición de las vías de cobro utilizadas:\n\n" .
                $pagoText . "\n" .
                "> **Evaluación de Riesgo:** Mantener una meta del 40%+ en cobros electrónicos para reducir manejo manual de efectivo.";
        }

        // General fallback
        return "### 🤖 Diagnóstico Integral de la IA\n\n" .
            "Basado en los datos actuales del sistema:\n\n" .
            "- **Recaudación Total:** \$" . number_format($kpis['total_recaudado'] ?? 0, 2) . " USD\n" .
            "- **Total de Trámites:** " . ($kpis['total_tramites'] ?? 0) . "\n" .
            "- **Oficina Principal:** " . ($kpis['top_oficina'] ?? 'Brooklyn') . "\n" .
            "- **Colaborador Destacado:** " . ($kpis['top_usuario'] ?? 'N/A') . "\n" .
            "- **Efectividad en Cajas:** " . ($kpis['porcentaje_cuadre'] ?? 100) . "%\n\n" .
            "Puedes consultar específicamente sobre: *rendimiento de usuarios, facturación de ventas, trámites más usados, métodos de pago, horarios pico u oficinas*.";
    }

    /**
     * Gemini API call helper.
     */
    private function callGeminiApi(array $analytics, string $instruction): ?array
    {
        try {
            $apiKey = config('services.gemini.key');
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $prompt = "Eres el Asistente Ejecutivo de Inteligencia Artificial para el sistema notarial NESISTEMA 2.0.
Analiza los siguientes datos analíticos de la notaría y genera un diagnóstico estratégico en formato JSON con las siguientes claves exactas:
- executive_summary (string con formato markdown)
- strategic_highlights (array de 4 strings con formato markdown)
- operational_bottlenecks (array de 2-3 strings con formato markdown)
- actionable_recommendations (array de objetos con prioridad, color, titulo, descripcion)

DATOS DEL SISTEMA:
" . json_encode($analytics, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            $response = Http::timeout(10)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    $parsed = json_decode($text, true);
                    if ($parsed && isset($parsed['executive_summary'])) {
                        $parsed['engine'] = 'Google Gemini AI (Cloud Engine)';
                        $parsed['generated_at'] = now()->format('d/m/Y H:i:s');
                        return $parsed;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error en llamada a Gemini API: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Gemini API custom Q&A helper.
     */
    private function callGeminiForQuestion(string $question, array $analytics): ?string
    {
        try {
            $apiKey = config('services.gemini.key');
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $prompt = "Eres el Asistente Ejecutivo de Inteligencia Artificial para el sistema notarial NESISTEMA 2.0.
Responde de manera ejecutiva, clara, precisa y profesional en español utilizando markdown con viñetas, negritas e insights a la siguiente pregunta del usuario basada en las métricas de la empresa:

PREGUNTA: {$question}

DATOS ACTUALES:
" . json_encode($analytics, JSON_UNESCAPED_UNICODE);

            $response = Http::timeout(10)->post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $body = $response->json();
                return $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
        } catch (\Exception $e) {
            Log::warning('Error en consulta a Gemini API: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * OpenAI API fallback helper.
     */
    private function callOpenAiApi(array $analytics, string $instruction): ?array
    {
        try {
            $apiKey = config('services.openai.key');
            $model = config('services.openai.model', 'gpt-4o-mini');

            $response = Http::timeout(10)->withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres el Asistente Ejecutivo de IA para NESISTEMA 2.0. Devuelve únicamente JSON estructurado.'],
                    ['role' => 'user', 'content' => "Analiza estos datos y devuelve JSON con executive_summary, strategic_highlights, operational_bottlenecks, actionable_recommendations:\n" . json_encode($analytics)]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $parsed = json_decode($content, true);
                if ($parsed && isset($parsed['executive_summary'])) {
                    $parsed['engine'] = 'OpenAI GPT-4o-mini (Cloud Engine)';
                    $parsed['generated_at'] = now()->format('d/m/Y H:i:s');
                    return $parsed;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error en llamada a OpenAI API: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * OpenAI API Q&A helper.
     */
    private function callOpenAiForQuestion(string $question, array $analytics): ?string
    {
        try {
            $apiKey = config('services.openai.key');
            $model = config('services.openai.model', 'gpt-4o-mini');

            $response = Http::timeout(10)->withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres el Asistente Ejecutivo de IA para NESISTEMA 2.0. Responde en español con formato markdown.'],
                    ['role' => 'user', 'content' => "PREGUNTA: {$question}\n\nDATOS:\n" . json_encode($analytics)]
                ]
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }
        } catch (\Exception $e) {
            Log::warning('Error en consulta OpenAI API: ' . $e->getMessage());
        }

        return null;
    }
}
