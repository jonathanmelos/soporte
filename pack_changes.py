# pack_changes.py
import os
import subprocess
import zipfile
from pathlib import Path
from datetime import datetime

def get_changed_files():
    # Usa git status --porcelain para obtener archivos M y ??
    result = subprocess.run(
        ["git", "status", "--porcelain"],
        capture_output=True,
        text=True,
        check=True,
    )
    files = []
    for line in result.stdout.splitlines():
        if not line:
            continue
        status = line[:2]
        path = line[3:].strip()
        # ignorar borrados
        if "D" in status:
            continue
        files.append(path)
    return files

def add_path_to_zip(zipf, base_dir, rel_path):
    full_path = base_dir / rel_path
    if full_path.is_dir():
        for root, _, filenames in os.walk(full_path):
            for name in filenames:
                fpath = Path(root) / name
                zipf.write(fpath, fpath.relative_to(base_dir))
    elif full_path.is_file():
        zipf.write(full_path, rel_path)

def main():
    repo = Path(__file__).resolve().parent
    files = get_changed_files()
    if not files:
        print("No hay cambios para empaquetar.")
        return

    # Save the zip next to this script (repo root)
    desktop = repo
    stamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    zip_path = desktop / f"soporte_changes_{stamp}.zip"

    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as zipf:
        for rel in files:
            add_path_to_zip(zipf, repo, rel)

    print(f"ZIP creado: {zip_path}")

if __name__ == "__main__":
    main()
