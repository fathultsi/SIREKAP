from fastapi import FastAPI, Request
from fastapi.responses import FileResponse
from jinja2 import Environment, FileSystemLoader
from weasyprint import HTML
from tempfile import NamedTemporaryFile
import os

app = FastAPI()
env = Environment(loader=FileSystemLoader('templates'))

@app.post("/generate-pdf")
async def generate_pdf(request: Request):
    data = await request.json()
    template = env.get_template("rekap-uang-makan.html")
    rendered_html = template.render(pegawai=data["pegawai"], bulanTahun=data["bulanTahun"])

    with NamedTemporaryFile(delete=False, suffix=".pdf") as tmp_file:
        HTML(string=rendered_html).write_pdf(tmp_file.name)
        return FileResponse(tmp_file.name, filename="rekap.pdf", media_type="application/pdf")
