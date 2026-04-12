<?php 
session_start();
require __DIR__ . "/../header.php";
?>

<!-- Contenido principal -->
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
  
  <!-- Declaración de privacidad -->
  <div class="nes-container is-rounded with-title" style="background-color: #f9f9f9; margin-bottom: 40px;">
    <p class="title">Declaración de Privacidad</p>
    
    <div style="padding: 20px; line-height: 1.6;">
      <h3 style="color: #2c3e50; font-size: 1.5rem;">1. Información que recopilamos</h3>
      <p>En nuestra Pokédex, recopilamos la información necesaria para gestionar tu cuenta de entrenador Pokémon, incluyendo tu nombre de entrenador, correo electrónico y los Pokémon que registras en tu colección personal.</p>
      
      <h3 style="color: #2c3e50; font-size: 1.5rem; margin-top: 20px;">2. Uso de la información</h3>
      <p>Los datos recopilados se utilizan exclusivamente para gestionar tu cuenta de entrenador</p>
      </ul>
      
      <h3 style="color: #2c3e50; font-size: 1.5rem; margin-top: 20px;">3. Protección de datos</h3>
      <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tu información contra acceso no autorizado, pérdida o alteración.</p>
      
      <h3 style="color: #2c3e50; font-size: 1.5rem; margin-top: 20px;">4. Compartición de datos</h3>
      <p>No compartimos tu información personal con terceros, excepto cuando sea requerido por ley o para proteger nuestros derechos.</p>
      
      <h3 style="color: #2c3e50; font-size: 1.5rem; margin-top: 20px;">5. Tus derechos</h3>
      <p>Tienes derecho a acceder, rectificar o eliminar tus datos personales en cualquier momento. Para ejercer estos derechos, contacta con nuestro equipo de soporte.</p>
      
      <p style="margin-top: 30px; font-style: italic; color: #666;">Última actualización: Abril 2026</p>
    </div>
  </div>
  
  <!-- Equipo de trabajo -->
  <div class="nes-container is-rounded with-title" style="background-color: #f0f0f0;">
    <p class="title">Equipo de Desarrollo</p>
    
    <div style="padding: 20px;">
      <p style="text-align: center; margin-bottom: 30px; font-size: 1.1rem;">
        Este proyecto fue realizado por el siguiente equipo:
      </p>
      
      <div class="row" style="display: flex; flex-wrap: wrap; gap: 25px; justify-content: center;">
        
        <!-- Front-end -->
        <div class="col" style="flex: 1 1 250px;">
          <div class="nes-container is-rounded" style="background-color: #E8F5E9; text-align: center;">
            <h4 style="color: #2E7D32; margin-bottom: 15px;">
              Front-end
            </h4>
            <div style="border-top: 2px solid #4CAF50; margin: 10px 0;"></div>
            <ul style="list-style: none; padding: 0;">
              <li style="padding: 8px 0;">Juan Gutiérrez Reyes</li>
              <li style="padding: 8px 0;">_________________</li>
              <li style="padding: 8px 0;">_________________</li>
            </ul>
          </div>
        </div>
        
        <!-- Back-end -->
        <div class="col" style="flex: 1 1 250px;">
          <div class="nes-container is-rounded" style="background-color: #FFF3E0; text-align: center;">
            <h4 style="color: #E65100; margin-bottom: 15px;">
              Back-end
            </h4>
            <div style="border-top: 2px solid #FF9800; margin: 10px 0;"></div>
            <ul style="list-style: none; padding: 0;">
              <li style="padding: 8px 0;">Sergio Adriel</li>
              <li style="padding: 8px 0;">Leonardo Apolonio Villagómez</li>
              <li style="padding: 8px 0;">_________________</li>
            </ul>
          </div>
        </div>
        
        <!-- Base de Datos -->
        <div class="col" style="flex: 1 1 250px;">
          <div class="nes-container is-rounded" style="background-color: #E3F2FD; text-align: center;">
            <h4 style="color: #1565C0; margin-bottom: 15px;">
              Base de Datos
            </h4>
            <div style="border-top: 2px solid #2196F3; margin: 10px 0;"></div>
            <ul style="list-style: none; padding: 0;">
              <li style="padding: 8px 0;">Irving</li>
              <li style="padding: 8px 0;">_________________</li>
              <li style="padding: 8px 0;">_________________</li>
            </ul>
          </div>
        </div>
        
        <!-- QA / Testing -->
        <div class="col" style="flex: 1 1 250px;">
          <div class="nes-container is-rounded" style="background-color: #FCE4EC; text-align: center;">
            <h4 style="color: #880E4F; margin-bottom: 15px;">
              QA / Testing
            </h4>
            <div style="border-top: 2px solid #E91E63; margin: 10px 0;"></div>
            <ul style="list-style: none; padding: 0;">
              <li style="padding: 8px 0;">_________________</li>
              <li style="padding: 8px 0;">_________________</li>
              <li style="padding: 8px 0;">_________________</li>
            </ul>
          </div>
        </div>
        
      </div>
      
      <!-- Nombre del equipo -->
      <div style="text-align: center; margin-top: 40px; padding: 20px; background-color: #fff3e0; border-radius: 15px;">
        <h3 style="color: #ff6f00;">
          Nombre del Equipo
        </h3>
        <div style="border: 2px dashed #ff9800; padding: 15px; margin-top: 10px; border-radius: 10px;">
          <p style="font-size: 1.8rem; font-weight: bold; color: #e65100;">Chamomile</p>
        </div>
      </div>
      
    </div>
  </div>
  
  <!-- Botón para volver -->
  <div class="center" style="margin-top: 40px; margin-bottom: 40px;">
    <a href="../index.php" class="btn-large waves-effect waves-light blue darken-2 hoverable">
      Volver
    </a>
  </div>
  
</div>

<?php
require __DIR__ . "/../footer.php";
?>