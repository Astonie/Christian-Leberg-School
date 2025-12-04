import importlib.util, sys, traceback
files = [r'C:\Users\THINKPAD -T15\helpdesk\backend\asset_management\core\models.py', r'C:\Users\THINKPAD -T15\helpdesk\backend\asset_management\plugins\base.py', r'C:\Users\THINKPAD -T15\helpdesk\backend\app\api\v1\endpoints\assets_crud.py']
ok = True
for f in files:
    try:
        spec = importlib.util.spec_from_file_location('mod', f)
        m = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(m)
        print(f'{f}: OK')
    except Exception:
        ok = False
        print(f'{f}: FAILED')
        traceback.print_exc()
sys.exit(0 if ok else 1
