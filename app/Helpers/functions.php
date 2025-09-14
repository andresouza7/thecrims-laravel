<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('handleRequest')) {
    /**
     * Wraps a request handler with try/catch and redirects back with a message.
     *
     * @param  callable  $callback
     * @param  string|null  $successMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    function handleRequest(callable $callback, ?string $successMessage = null)
    {
        try {
            $callback();

            return back()->with('message', $successMessage ?? 'Operação realizada com sucesso!');
            // return redirect()->back()->with('message', $successMessage ?? 'Operação realizada com sucesso!');
        } catch (\Throwable $th) {
            Log::error('Request handling failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return back()->with('error', $th->getMessage());
        }
    }
}
