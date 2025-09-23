// server.js
import express from "express";
import fetch from "node-fetch"; // npm install node-fetch

const app = express();
const PORT = 3000;

app.get("/inspect", async (req, res) => {
  const url = req.query.url;
  if (!url) {
    return res.status(400).json({ success: "false", error: "Please provide a URL" });
  }

  try {
    const response = await fetch(url);
    const text = await response.text();

    // Simple JSON output
    const result = {
      message: text.slice(0, 200) // sirf pehle 200 characters for demo
    };

    res.json({
      success: "true",
      inspect: result
    });
  } catch (err) {
    res.status(500).json({ success: "false", error: err.message });
  }
});

app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});