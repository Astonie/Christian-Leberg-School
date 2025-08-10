"use client";

import { Logo } from "./Logo";
import { NavList } from "./NavList";

export function Sidebar() {
  return (
    <aside className="hidden md:flex md:w-64 md:flex-col border-r bg-background">
      <div className="h-14 px-4 flex items-center border-b">
        <Logo />
      </div>
      <NavList />
      <div className="p-4 border-t text-xs text-muted-foreground">© {new Date().getFullYear()} Uplifta Microfinance</div>
    </aside>
  );
}


