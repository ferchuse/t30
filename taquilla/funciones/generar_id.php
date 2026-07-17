<?php
	
	function generarIDconVerificador($fecha_hora) {
		// 1️⃣ Base = solo fecha y hora
		$base = $fecha_hora;
		
		// 2️⃣ Hash (md5 → hexadecimal → decimal)
		$hash = substr(md5($base), 0, 8);
		$decimal = hexdec($hash);
		
		// 3️⃣ Reducir a 6 dígitos
		$id6 = $decimal % 1000000;
		$id6 = str_pad($id6, 6, '0', STR_PAD_LEFT);
		
		// 4️⃣ Calcular dígito verificador (módulo 11)
		$verificador = calcularDigitoVerificador($id6);
		
		// 5️⃣ Retornar el ID completo
		return abs($id6 . $verificador);
	}
	
	function calcularDigitoVerificador($numero) {
		// Módulo 11
		$factor = 2;
		$suma = 0;
		
		for ($i = strlen($numero) - 1; $i >= 0; $i--) {
			$suma += intval($numero[$i]) * $factor;
			$factor = ($factor == 7) ? 2 : $factor + 1;
		}
		
		$resto = $suma % 11;
		$dv = 11 - $resto;
		
		if ($dv == 10) return '1';
		if ($dv == 11) return '0';
		return strval($dv);
	}
	/*
	// Ejemplo de uso
	$fecha_hora = "2025-06-02 18:45:00";
	
	$id_final = generarIDconVerificador($fecha_hora);
	
	echo "ID generado: " . $id_final; // Ejemplo: 5841237
	
	*/


?>