# Gym Management System - Core Architecture 🏋️‍♂️

Este proyecto representa la fase inicial de una plataforma integral para la gestión de centros deportivos. Ha sido desarrollado con un enfoque de **"Base Escalable"**, priorizando la modularidad del código y la integridad de los datos para permitir un crecimiento orgánico tanto en funcionalidades como en volumen de usuarios.

## 🚀 Visión de Escalabilidad

A diferencia de un sistema cerrado, esta arquitectura fue diseñada bajo los siguientes pilares para asegurar su evolución:

### 1. Diseño de Base de Datos Normalizada
El esquema de la base de datos sigue las reglas de normalización (3NF) para evitar redundancias y asegurar la integridad referencial. Está preparada para soportar:
- **Crecimiento Vertical:** Optimización de consultas (`JOINs` eficientes e índices) para manejar miles de registros de asistencia sin pérdida de rendimiento.
- **Relaciones Flexibles:** La estructura permite añadir nuevas entidades (como "Pagos", "Proveedores" o "Inventario") sin necesidad de reestructurar las tablas existentes.

### 2. Arquitectura Modular (Backend)
El código está organizado de forma que la lógica de negocio (asistencia, reservas, autenticación) está separada. Esto facilita:
- **Implementación de API:** La base actual permite transicionar fácilmente hacia una arquitectura de Microservicios o una API REST para conectar aplicaciones móviles en el futuro.
- **Mantenibilidad:** Cada módulo funciona de forma independiente, lo que permite actualizar la lógica de "Reservas" sin afectar el módulo de "Login".

### 3. Roadmap de Escalabilidad (Futuras Implementaciones)
Este MVP (Producto Mínimo Viable) es el cimiento para las siguientes fases:
- **Integración de Pasarelas de Pago:** Estructura lista para vincular servicios como Mercado Pago o PayPal.
- **Dashboard de Business Intelligence:** Datos estructurados para ser consumidos por herramientas de análisis (como Power BI) para reportes gerenciales.
- **Sistema de Roles Avanzado:** Preparado para escalar de una gestión simple a una multi-sede con diferentes niveles de permisos.

## 🛠️ Stack Tecnológico
- **Lenguaje:** PHP (Arquitectura de scripts modulares).
- **Motor de Base de Datos:** MySQL / MariaDB.
- **Frontend:** HTML5, CSS3 (Variables :root) y JavaScript (Vanilla).
- **Control de Versiones:** Git & GitHub.

## 🔒 Gestión de Concurrencia y Consistencia

Uno de los mayores desafíos técnicos resueltos en este sistema es la gestión de **Reservas Simultáneas**. Para evitar el "Overbooking" (sobreventa de cupos), se implementó una estrategia de control de concurrencia a nivel de base de datos:

### Implementación Técnica:
- **Transacciones ACID:** Las operaciones de reserva se ejecutan dentro de transacciones SQL (`START TRANSACTION`). Esto asegura que, ante cualquier fallo en el proceso (como una caída de conexión), la base de datos realice un `ROLLBACK` automático, manteniendo la integridad de los datos.
- **Validación Atómica de Cupos:** En lugar de realizar una lectura y luego una escritura por separado, el sistema verifica la disponibilidad en el momento exacto del registro. 
- **Control de Condiciones de Carrera (Race Conditions):** Se diseñó la lógica de reserva para que el decremento del cupo sea una operación atómica, garantizando que si dos usuarios intentan reservar el último lugar al mismo tiempo, solo uno tenga éxito y el otro reciba una excepción controlada.

### Ejemplo de Lógica SQL Aplicada:
```sql
-- Ejemplo conceptual de la transacción de reserva
START TRANSACTION;
  -- 1. Verificamos cupo disponible
  SELECT cupo_disponible FROM clases WHERE id = :id_clase FOR UPDATE;
  
  -- 2. Si hay lugar, insertamos reserva y actualizamos cupo
  INSERT INTO reservas (id_socio, id_clase) VALUES (:id_socio, :id_clase);
  UPDATE clases SET cupo_disponible = cupo_disponible - 1 WHERE id = :id_clase;
COMMIT;

## 📂 Estructura del Proyecto
- `/sql`: Scripts de creación, triggers y datos de prueba.
- `/src`: Lógica de servidor y conexión a base de datos.
- `/docs`: Documentación técnica y Diagrama Entidad-Relación (DER).

---
**Desarrollado como base tecnológica escalable para la gestión eficiente de datos deportivos.**
