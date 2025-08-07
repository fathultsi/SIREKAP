from fastapi import FastAPI, Request
from fastapi.responses import FileResponse
from pydantic import BaseModel
from typing import List
from jinja2 import Environment, FileSystemLoader
from weasyprint import HTML
import os, zipfile, uuid
from fastapi.staticfiles import StaticFiles

app = FastAPI()
app.mount("/static", StaticFiles(directory="templates/static"), name="static")
env = Environment(loader=FileSystemLoader("templates"))

class RekapRequest(BaseModel):
    pegawai_list: List[dict]
    bulan: str

@app.post("/generate-zip")
async def generate_zip(request: RekapRequest):
    bulan = request.bulan
    pegawai_list = request.pegawai_list

    output_dir = f"output/{uuid.uuid4()}"
    os.makedirs(output_dir, exist_ok=True)

    pdf_paths = []

    for p in pegawai_list:
        # template = env.get_template("rekap.html")
        # html_out = template.render(pegawai=p, base_url="http://127.0.0.1:8000")

        # # Simpan file PDF di output_dir
        # pdf_path = os.path.join(output_dir, f"{p['nama'].replace(' ', '_')}_{p['nip']}.pdf")

        # # Gunakan base_url untuk memastikan file CSS dari /static bisa dibaca
        # HTML(string=html_out, base_url="http://127.0.0.1:8000").write_pdf(pdf_path)
        template = env.get_template("rekap.html")
        html_out = template.render(pegawai=p)
        pdf_path = os.path.join(output_dir, f"{p['nama'].replace(' ', '_')}_{p['nip']}.pdf")
        HTML(string=html_out, base_url="templates").write_pdf(pdf_path)
        pdf_paths.append(pdf_path)



    zip_filename = f"rekap_uang_makan_{bulan}_{uuid.uuid4().hex}.zip"
    zip_path = os.path.join("output", zip_filename)

    with zipfile.ZipFile(zip_path, "w") as zipf:
        for pdf in pdf_paths:
            zipf.write(pdf, os.path.basename(pdf))

    return FileResponse(zip_path, media_type="application/zip", filename=zip_filename)
