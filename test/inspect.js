<!DOCTYPE html>
<html>
<head>
  <title>Inspect URL</title>
</head>
<body>
  <input type="text" id="url" placeholder="Enter URL" style="width:300px">
  <button onclick="inspectURL()">Inspect</button>
  <pre id="output"></pre>

  <script>
    async function inspectURL() {
      const url = document.getElementById('url').value;
      if (!url) return alert("Enter a URL");

      // CORS proxy use kar rahe
      const proxyURL = 'https://api.allorigins.win/get?url=' + encodeURIComponent(url);

      try {
        const response = await fetch(proxyURL);
        const data = await response.json();
        // Content JSON me wrap karke dikha rahe
        document.getElementById('output').textContent = JSON.stringify({
          success: "true",
          inspect: { content: data.contents }
        }, null, 2);
      } catch (err) {
        document.getElementById('output').textContent = JSON.stringify({
          success: "false",
          error: err.message
        }, null, 2);
      }
    }
  </script>
</body>
</html>