<template>
    <div>
      <h1 class="text-center font-bold text-4xl mb-1">QR Scanner</h1>
      <div class="text-center mb-3">If not working just hard refresh the page</div>
      <div id="reader" class="mx-auto" style="width: 400px;"></div>

      <div id="result" class="mt-3">

      </div>
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  import { Html5QrcodeScanner } from 'html5-qrcode';
  
  const onScanSuccess = (decodedText, decodedResult) => {
    // console.log(`Code matched = ${decodedText}`, decodedResult);
    // alert("ID Number: " + decodedText);
    document.getElementById('result').innerHTML = `${decodedText},`
  };
  
  const onScanFailure = (error) => {
    console.warn(`Code scan error = ${error}`);
  };
  
  onMounted(() => {
    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader",
      { fps: 10, qrbox: { width: 250, height: 250 } },
      false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
  });
  </script>
  
  <style>
  .qrscn {
    margin: 0 auto;
  }
  </style>