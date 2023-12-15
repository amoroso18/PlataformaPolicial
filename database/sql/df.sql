
INSERT INTO `dispocicion_fiscals` (`id`, `caso`, `nro`, `fecha_disposicion`, `fiscal_responsable_id`, `fiscal_asistente_id`, `oficial_acargo_id`, `resumen`, `observaciones`, `plazo_id`, `plazo`, `plazo_ampliacion`, `plazo_reduccion`, `fecha_inicio`, `fecha_termino`, `estado_id`, `referencia_fiscal_anterior`, `created_at`, `updated_at`) VALUES
(1, '000235', '01', '2023-12-14', 2, 1, 2, 'Esta es una prueba de registro de información.', 'Sin observaciones', 0, 20, NULL, NULL, '2023-12-15', '2024-01-04', 2, NULL, '2023-12-14 15:08:15', '2023-12-15 14:20:59'),
(2, 'CIA SAN JUAN DE MIRAFLORES', '04', '2022-01-27', 2, 1, 3, 'Realiza las pesquisas necesarias en el Jirón Gregorio Montes, con referencia a la altura del mercado Ciudad de Dios, en el distrito de San Juan de Miraflores, a fin de permitir mayores y nuevos elementos de convicción en la presente investigación por el plazo de 20 días, diligencia que estará a cargo de la DIRCOCOR - Lima Sur, en coordinación con personal policial de la División de Inteligencia Anticorrupción de la Dirección contra la corrupción (DIVINANT - DIRCOCOR), a efectos de que practiquen las técnicas especiales de investigación como operaciones de video vigilancia y pesquisas que sean necesarias.', 'Sin observaciones', 0, 20, NULL, NULL, '2022-01-28', '2022-02-17', 1, NULL, '2023-12-15 14:42:51', '2023-12-15 14:42:51'),
(3, 'CIA SAN JUAN DE MIRAFLORES', '03', '2022-09-06', 2, 1, 1, 'Realizar OVISE o Video Vigilancia por elplazo de 20 días al ST1 PNP Claudio Aristides Rabanal Apaza y a la ciudadana Rosmeri Curo Marallano con el fin de establecer vínculo o relacion existente.', 'OVISE al Comisario de San Juan de Miraflores', 0, 20, NULL, NULL, '2022-09-07', '2022-09-27', 1, NULL, '2023-12-15 14:57:00', '2023-12-15 14:57:00');



INSERT INTO `disposicion_fiscal_delitos` (`id`, `df_id`, `delitos_id`, `users_id`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, NULL, 1, '2023-12-14 15:08:15', '2023-12-14 15:08:15'),
(2, 1, 12, 1, NULL, 1, '2023-12-14 15:08:15', '2023-12-14 15:08:15'),
(3, 2, 7, 1, NULL, 1, '2023-12-15 14:42:51', '2023-12-15 14:42:51'),
(4, 3, 12, 1, NULL, 1, '2023-12-15 14:57:00', '2023-12-15 14:57:00');


INSERT INTO `disposicion_fiscal_doc_resultados` (`id`, `df_id`, `documentos_id`, `users_id`, `fecha_documento`, `asunto`, `resultadoFinal`, `destino`, `archivo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2023-12-15', 'Documento final de prueba', 'Expediente culminado con las actividades', 'Fiscalia XXXXX', '17026500591.pdf', 1, '2023-12-15 14:20:59', '2023-12-15 14:20:59');


INSERT INTO `disposicion_fiscal_entidad_vigilancias` (`id`, `df_id`, `users_id`, `entidads_id`, `codigo_relacion`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 1, NULL, 1, '2023-12-14 15:08:15', '2023-12-14 15:08:15'),
(2, 1, 1, 1, 314, NULL, 1, '2023-12-14 15:08:15', '2023-12-14 15:08:15'),
(3, 2, 1, 4, 5, NULL, 1, '2023-12-15 14:42:51', '2023-12-15 14:42:51'),
(4, 3, 1, 1, 316, NULL, 1, '2023-12-15 14:57:00', '2023-12-15 14:57:00'),
(5, 3, 1, 1, 317, NULL, 1, '2023-12-15 14:57:00', '2023-12-15 14:57:00');


INSERT INTO `disposicion_fiscal_nueva_vigilancias` (`id`, `df_id`, `users_id`, `documentos_id`, `numeroDocumento`, `siglasDocumento`, `fechaDocumento`, `asunto`, `respondea`, `evaluacion`, `conclusiones`, `archivo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '235', '-2023-XXXX', '2023-12-17', 'VIDEOVIGILANCIA XXX', 'PLAN 45433', 'AA1', 'ESTA ES UNA PRUEBA DE REGISTRO DE INFORMACION DE LOS SEGUIMIENTOS QUE SE ESTAN REALIZANDO A MERITO DE LA DISPOSICION FISCAL', '17025665971.pdf', 1, '2023-12-14 15:09:57', '2023-12-14 15:09:57'),
(2, 3, 1, 1, '249', '-09-2022-1L2M/MB48213', '2022-09-29', 'Prueba', 'PAI. “DIRIN-2022” “BIUTRO III” del 29SET2022.', 'AA1', 'Prueba', '17026532681.pdf', 1, '2023-12-15 15:14:27', '2023-12-15 15:14:28');


INSERT INTO `disposicion_fiscal_nueva_vigilancia_actividads` (`id`, `dfnv_id`, `users_id`, `fechahora`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-12-16 06:00:00', 1, '2023-12-14 15:10:33', '2023-12-14 15:10:33'),
(2, 2, 1, '2022-09-07 06:00:00', 1, '2023-12-15 15:15:37', '2023-12-15 15:15:37'),
(3, 2, 1, '2022-09-07 06:50:00', 1, '2023-12-15 15:29:23', '2023-12-15 15:29:23'),
(4, 2, 1, '2022-09-07 10:53:00', 1, '2023-12-15 15:32:23', '2023-12-15 15:32:23');

INSERT INTO `disposicion_fiscal_nueva_vigilancia_entidads` (`id`, `dfnva_id`, `users_id`, `entidads_id`, `codigo_relacion`, `detalle`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 315, 'EL SUJETO SALIO DE SU DOMICILIO CON DESTINO AL PARADERO SITUADO EN LA ESQUINA DE SU CASA.', 1, '2023-12-14 15:11:44', '2023-12-14 15:11:44'),
(2, 1, 1, 4, 1, 'hecho delictivo en la zona', 1, '2023-12-14 15:20:54', '2023-12-14 15:20:54'),
(3, 1, 1, 4, 4, 'prueba 2', 1, '2023-12-14 15:25:15', '2023-12-14 15:25:15'),
(4, 2, 1, 1, 317, 'Se desarrollo OVISE a inmediaciones del inmueble de la persona indicada denominada como el B/O ROSMERY la misma que según la consulta realizada en la base de datos RENIEC registra inmueble en el AA.HH Túpac Amaru de Villa Mz. “A” Lte. 2 – Chorrillos – Lima. Constituido el personal 1L2M por el Asentamiento Humano y al realizar la búsqueda de la Mz. “A”, se tomó conocimiento que las Manzanas A, A1 y A2 se encontraban por la Av. Perú, Av. 24 de junio, Av. San Juan, Av. Velasco Alvarado y el Pasaje Pumacahua; Es así que el personal procedió a realizar la búsqueda de información por inmediaciones de dichas avenidas.', 1, '2023-12-15 15:18:09', '2023-12-15 15:18:09'),
(5, 3, 1, 4, 7, 'Vivienda del B/O Rosmery', 1, '2023-12-15 15:31:23', '2023-12-15 15:31:23'),
(6, 4, 1, 4, 7, 'Vivienda del B/O Rosmery', 1, '2023-12-15 15:35:35', '2023-12-15 15:35:35');


INSERT INTO `disposicion_fiscal_nueva_vigilancia_archivos` (`id`, `dfnve_id`, `users_id`, `ta_id`, `archivo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '17025667040.jpeg', 1, '2023-12-14 15:11:44', '2023-12-14 15:11:44'),
(2, 1, 1, 1, '17025667041.jpeg', 1, '2023-12-14 15:11:44', '2023-12-14 15:11:44'),
(3, 2, 1, 1, '17025672540.PNG', 1, '2023-12-14 15:20:54', '2023-12-14 15:20:54'),
(4, 3, 1, 1, '17025675150.jpg', 1, '2023-12-14 15:25:15', '2023-12-14 15:25:15'),
(5, 4, 1, 1, '17026534890.JPG', 1, '2023-12-15 15:18:09', '2023-12-15 15:18:09'),
(6, 5, 1, 1, '17026542830.JPG', 1, '2023-12-15 15:31:23', '2023-12-15 15:31:23'),
(7, 6, 1, 1, '17026545350.JPG', 1, '2023-12-15 15:35:35', '2023-12-15 15:35:35');


INSERT INTO `disposicion_fiscal_referencias` (`id`, `df_id`, `documentos_id`, `users_id`, `nro`, `fecha_documento`, `siglas`, `siglas_referencia_anterior`, `pdf`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1, '706015500', NULL, '-2021-142-0', NULL, NULL, NULL, 1, '2023-12-15 14:42:51', '2023-12-15 14:42:51'),
(2, 3, 3, 1, '123', NULL, '-2022', NULL, NULL, NULL, 1, '2023-12-15 14:57:00', '2023-12-15 14:57:00');


INSERT INTO `disposicion_fiscal_tipo_video_vigilancias` (`id`, `df_id`, `vv_id`, `users_id`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 1, '2023-12-14 15:08:15', '2023-12-14 15:08:15'),
(2, 3, 2, 1, NULL, 1, '2023-12-15 14:57:00', '2023-12-15 14:57:00');

