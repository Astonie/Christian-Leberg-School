"use client";

import { useState } from "react";
import { format } from "date-fns";
import { Calendar as CalendarIcon } from "lucide-react";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { DayPicker } from "react-day-picker";
import "react-day-picker/style.css";

export function DatePicker({ value, onChange, label = "Pick a date" }: { value?: Date; onChange?: (d?: Date) => void; label?: string }) {
  const [open, setOpen] = useState(false);
  const date = value;
  return (
    <Popover open={open} onOpenChange={setOpen}>
      <PopoverTrigger asChild>
        <Button variant="outline" className="justify-start gap-2 font-normal">
          <CalendarIcon className="h-4 w-4" />
          {date ? format(date, "PPP") : <span className="text-muted-foreground">{label}</span>}
        </Button>
      </PopoverTrigger>
      <PopoverContent>
        <DayPicker
          mode="single"
          selected={date}
          onSelect={(d) => {
            onChange?.(d);
            setOpen(false);
          }}
          weekStartsOn={1}
        />
      </PopoverContent>
    </Popover>
  );
}


