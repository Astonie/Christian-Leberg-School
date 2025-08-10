import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";

export function middleware(request: NextRequest) {
  const isLoggedIn = true; // TODO: replace with real auth check
  const { pathname } = request.nextUrl;
  const isAuthPath = pathname.startsWith("/auth") || pathname === "/";
  if (!isLoggedIn && !isAuthPath) {
    const url = new URL("/auth/login", request.url);
    return NextResponse.redirect(url);
  }
  return NextResponse.next();
}

export const config = {
  matcher: ["/((?!_next|api|.*\\..*).*)"],
};


