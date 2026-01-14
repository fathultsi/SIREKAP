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

    # folder utama per bulan
    base_output_dir = f"output/{bulan}"
    os.makedirs(base_output_dir, exist_ok=True)

    pdf_paths = []

    for p in pegawai_list:
        status_map = p.get("STATUS_MAP", "PPPK_TAHAP_2")  # default kalau tidak ada
        # bikin folder per status map
        status_dir = os.path.join(base_output_dir, status_map)
        os.makedirs(status_dir, exist_ok=True)

        template = env.get_template("rekap.html")
        html_out = template.render(pegawai=p)

        # simpan pdf di folder sesuai status_map
        pdf_filename = f"{p['nama'].replace(' ', '_')}_{p['nip']}.pdf"
        pdf_path = os.path.join(status_dir, pdf_filename)
        HTML(string=html_out, base_url="templates").write_pdf(pdf_path)

        pdf_paths.append(pdf_path)

    # buat zip dari folder base_output_dir
    zip_filename = f"rekap_uang_makan_{bulan}.zip"
    zip_path = os.path.join("output", zip_filename)

    with zipfile.ZipFile(zip_path, "w") as zipf:
        for root, _, files in os.walk(base_output_dir):
            for file in files:
                if file.endswith(".pdf"):
                    file_path = os.path.join(root, file)
                    # relative path agar folder tetap ada di zip
                    arcname = os.path.relpath(file_path, base_output_dir)
                    zipf.write(file_path, arcname)

    return FileResponse(zip_path, media_type="application/zip", filename=zip_filename)

@app.post("/generate_kehadiran")
async def generate_kehadiran(request: RekapRequest):
    bulan = request.bulan
    pegawai_list = request.pegawai_list

    # folder utama per bulan
    base_output_dir = f"output_kehadiran/{bulan}"
    os.makedirs(base_output_dir, exist_ok=True)

    pdf_paths = []
    bulan_split = bulan.split(" ")
    bulanTahun = {
        "BULAN": bulan_split[0],
        "TAHUN": bulan_split[1] if len(bulan_split) > 1 else ""
    }

    for p in pegawai_list:
        status_map = p.get("STATUS_MAP", "PPPK_TAHAP_2")  # default kalau kosong
        # bikin folder berdasarkan status map
        status_dir = os.path.join(base_output_dir, status_map)
        os.makedirs(status_dir, exist_ok=True)

        template = env.get_template("rekap_kehadiran.html")
        html_out = template.render(pegawai=p, bulanTahun=bulanTahun)

        # simpan pdf di folder sesuai status_map
        pdf_filename = f"{p['NAMA'].replace(' ', '_')}_{p['NIP']}.pdf"
        pdf_path = os.path.join(status_dir, pdf_filename)
        HTML(string=html_out, base_url="templates").write_pdf(pdf_path)

        pdf_paths.append(pdf_path)

    # buat zip dari folder base_output_dir
    zip_filename = f"rekap_kehadiran_{bulan}.zip"
    zip_path = os.path.join("output_kehadiran", zip_filename)

    with zipfile.ZipFile(zip_path, "w") as zipf:
        for root, _, files in os.walk(base_output_dir):
            for file in files:
                if file.endswith(".pdf"):
                    file_path = os.path.join(root, file)
                    # simpan path relatif agar ada struktur folder di zip
                    arcname = os.path.relpath(file_path, base_output_dir)
                    zipf.write(file_path, arcname)

    return FileResponse(zip_path, media_type="application/zip", filename=zip_filename)
