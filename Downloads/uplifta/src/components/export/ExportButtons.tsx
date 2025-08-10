"use client";

import { Button } from "@/components/ui/button";
import * as XLSX from "xlsx";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export function ExportButtons<T extends Record<string, unknown>>({ data, fileBase = "export" }: { data: T[]; fileBase?: string }) {
  const exportExcel = () => {
    const ws = XLSX.utils.json_to_sheet(data as Array<Record<string, unknown>>);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Data");
    XLSX.writeFile(wb, `${fileBase}.xlsx`);
  };
  const exportPdf = () => {
    const doc = new jsPDF();
    const head: string[] = Object.keys((data?.[0] as Record<string, unknown>) || {});
    const body: Array<Array<string | number | boolean | null>> = data.map((row) =>
      head.map((key) => {
        const value = row[key];
        if (typeof value === "string" || typeof value === "number" || typeof value === "boolean") return value;
        if (value == null) return null;
        return String(value);
      })
    );
    autoTable(doc, { head: [head], body });
    doc.save(`${fileBase}.pdf`);
  };
  return (
    <div className="flex gap-2">
      <Button variant="outline" size="sm" onClick={exportExcel}>Export Excel</Button>
      <Button variant="outline" size="sm" onClick={exportPdf}>Export PDF</Button>
    </div>
  );
}


