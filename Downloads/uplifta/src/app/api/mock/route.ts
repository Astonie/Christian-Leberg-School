import { NextResponse } from "next/server";

export async function GET() {
  // Simple mock endpoint used during development
  return NextResponse.json({ ok: true, message: "Mock API is running" });
}


