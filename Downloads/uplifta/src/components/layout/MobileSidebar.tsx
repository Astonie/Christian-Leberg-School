"use client";

import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { Button } from "@/components/ui/button";
import { Menu } from "lucide-react";
import { Logo } from "./Logo";
import { NavList } from "./NavList";

export function MobileSidebar() {
  return (
    <div className="md:hidden">
      <Sheet>
        <SheetTrigger asChild>
          <Button variant="ghost" size="icon" aria-label="Open menu">
            <Menu className="h-5 w-5" />
          </Button>
        </SheetTrigger>
        <SheetContent side="left" className="p-0 w-72">
          <div className="h-14 px-4 flex items-center border-b">
            <Logo />
          </div>
          <NavList />
        </SheetContent>
      </Sheet>
    </div>
  );
}


