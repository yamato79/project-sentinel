import { PageProps as InertiaPageProps } from "@inertiajs/core";
import { AxiosInstance } from "axios";
import { route as ziggyRoute } from "ziggy-js";
import { PageProps as AppPageProps } from "./";

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    const route: typeof ziggyRoute;
}

declare module "vue" {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module "@inertiajs/core" {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module "@heroicons/vue/*" {
    import type { DefineComponent } from "vue";
    export const AcademicCapIcon: DefineComponent<{}, {}, any>;
    export const AdjustmentsIcon: DefineComponent<{}, {}, any>;
    export const AnnotationIcon: DefineComponent<{}, {}, any>;
    export const ArchiveIcon: DefineComponent<{}, {}, any>;
    export const ArrowCircleDownIcon: DefineComponent<{}, {}, any>;
    export const ArrowCircleLeftIcon: DefineComponent<{}, {}, any>;
    export const ArrowCircleRightIcon: DefineComponent<{}, {}, any>;
    export const ArrowCircleUpIcon: DefineComponent<{}, {}, any>;
    export const ArrowDownIcon: DefineComponent<{}, {}, any>;
    export const ArrowLeftIcon: DefineComponent<{}, {}, any>;
    export const ArrowNarrowDownIcon: DefineComponent<{}, {}, any>;
    export const ArrowNarrowLeftIcon: DefineComponent<{}, {}, any>;
    export const ArrowNarrowRightIcon: DefineComponent<{}, {}, any>;
    export const ArrowNarrowUpIcon: DefineComponent<{}, {}, any>;
    export const ArrowRightIcon: DefineComponent<{}, {}, any>;
    export const ArrowSmDownIcon: DefineComponent<{}, {}, any>;
    export const ArrowSmLeftIcon: DefineComponent<{}, {}, any>;
    export const ArrowSmRightIcon: DefineComponent<{}, {}, any>;
    export const ArrowSmUpIcon: DefineComponent<{}, {}, any>;
    export const ArrowUpIcon: DefineComponent<{}, {}, any>;
    export const ArrowsExpandIcon: DefineComponent<{}, {}, any>;
    export const AtSymbolIcon: DefineComponent<{}, {}, any>;
    export const BackspaceIcon: DefineComponent<{}, {}, any>;
    export const BadgeCheckIcon: DefineComponent<{}, {}, any>;
    export const BanIcon: DefineComponent<{}, {}, any>;
    export const Bars3Icon: DefineComponent<{}, {}, any>;
    export const BeakerIcon: DefineComponent<{}, {}, any>;
    export const BellIcon: DefineComponent<{}, {}, any>;
    export const BookOpenIcon: DefineComponent<{}, {}, any>;
    export const BookmarkAltIcon: DefineComponent<{}, {}, any>;
    export const BookmarkIcon: DefineComponent<{}, {}, any>;
    export const BriefcaseIcon: DefineComponent<{}, {}, any>;
    export const CakeIcon: DefineComponent<{}, {}, any>;
    export const CalculatorIcon: DefineComponent<{}, {}, any>;
    export const CalendarIcon: DefineComponent<{}, {}, any>;
    export const CameraIcon: DefineComponent<{}, {}, any>;
    export const CashIcon: DefineComponent<{}, {}, any>;
    export const ChartBarIcon: DefineComponent<{}, {}, any>;
    export const ChartPieIcon: DefineComponent<{}, {}, any>;
    export const ChartSquareBarIcon: DefineComponent<{}, {}, any>;
    export const ChatAlt2Icon: DefineComponent<{}, {}, any>;
    export const ChatAltIcon: DefineComponent<{}, {}, any>;
    export const ChatIcon: DefineComponent<{}, {}, any>;
    export const CheckCircleIcon: DefineComponent<{}, {}, any>;
    export const CheckIcon: DefineComponent<{}, {}, any>;
    export const ChevronDoubleDownIcon: DefineComponent<{}, {}, any>;
    export const ChevronDoubleLeftIcon: DefineComponent<{}, {}, any>;
    export const ChevronDoubleRightIcon: DefineComponent<{}, {}, any>;
    export const ChevronDoubleUpIcon: DefineComponent<{}, {}, any>;
    export const ChevronDownIcon: DefineComponent<{}, {}, any>;
    export const ChevronLeftIcon: DefineComponent<{}, {}, any>;
    export const ChevronRightIcon: DefineComponent<{}, {}, any>;
    export const ChevronUpIcon: DefineComponent<{}, {}, any>;
    export const ChipIcon: DefineComponent<{}, {}, any>;
    export const ClipboardCheckIcon: DefineComponent<{}, {}, any>;
    export const ClipboardCopyIcon: DefineComponent<{}, {}, any>;
    export const ClipboardListIcon: DefineComponent<{}, {}, any>;
    export const ClipboardIcon: DefineComponent<{}, {}, any>;
    export const ClockIcon: DefineComponent<{}, {}, any>;
    export const CloudDownloadIcon: DefineComponent<{}, {}, any>;
    export const CloudUploadIcon: DefineComponent<{}, {}, any>;
    export const CloudIcon: DefineComponent<{}, {}, any>;
    export const CodeIcon: DefineComponent<{}, {}, any>;
    export const CogIcon: DefineComponent<{}, {}, any>;
    export const Cog6ToothIcon: DefineComponent<{}, {}, any>;
    export const CollectionIcon: DefineComponent<{}, {}, any>;
    export const ColorSwatchIcon: DefineComponent<{}, {}, any>;
    export const CreditCardIcon: DefineComponent<{}, {}, any>;
    export const CubeTransparentIcon: DefineComponent<{}, {}, any>;
    export const CubeIcon: DefineComponent<{}, {}, any>;
    export const CurrencyBangladeshiIcon: DefineComponent<{}, {}, any>;
    export const CurrencyDollarIcon: DefineComponent<{}, {}, any>;
    export const CurrencyEuroIcon: DefineComponent<{}, {}, any>;
    export const CurrencyPoundIcon: DefineComponent<{}, {}, any>;
    export const CurrencyRupeeIcon: DefineComponent<{}, {}, any>;
    export const CurrencyYenIcon: DefineComponent<{}, {}, any>;
    export const CursorClickIcon: DefineComponent<{}, {}, any>;
    export const DatabaseIcon: DefineComponent<{}, {}, any>;
    export const DesktopComputerIcon: DefineComponent<{}, {}, any>;
    export const DeviceMobileIcon: DefineComponent<{}, {}, any>;
    export const DeviceTabletIcon: DefineComponent<{}, {}, any>;
    export const DocumentAddIcon: DefineComponent<{}, {}, any>;
    export const DocumentDownloadIcon: DefineComponent<{}, {}, any>;
    export const DocumentDuplicateIcon: DefineComponent<{}, {}, any>;
    export const DocumentRemoveIcon: DefineComponent<{}, {}, any>;
    export const DocumentReportIcon: DefineComponent<{}, {}, any>;
    export const DocumentSearchIcon: DefineComponent<{}, {}, any>;
    export const DocumentTextIcon: DefineComponent<{}, {}, any>;
    export const DocumentIcon: DefineComponent<{}, {}, any>;
    export const DotsCircleHorizontalIcon: DefineComponent<{}, {}, any>;
    export const DotsHorizontalIcon: DefineComponent<{}, {}, any>;
    export const DotsVerticalIcon: DefineComponent<{}, {}, any>;
    export const DownloadIcon: DefineComponent<{}, {}, any>;
    export const DuplicateIcon: DefineComponent<{}, {}, any>;
    export const EmojiHappyIcon: DefineComponent<{}, {}, any>;
    export const EmojiSadIcon: DefineComponent<{}, {}, any>;
    export const ExclamationCircleIcon: DefineComponent<{}, {}, any>;
    export const ExclamationIcon: DefineComponent<{}, {}, any>;
    export const ExternalLinkIcon: DefineComponent<{}, {}, any>;
    export const EyeOffIcon: DefineComponent<{}, {}, any>;
    export const EyeIcon: DefineComponent<{}, {}, any>;
    export const FastForwardIcon: DefineComponent<{}, {}, any>;
    export const FilmIcon: DefineComponent<{}, {}, any>;
    export const FilterIcon: DefineComponent<{}, {}, any>;
    export const FingerPrintIcon: DefineComponent<{}, {}, any>;
    export const FireIcon: DefineComponent<{}, {}, any>;
    export const FlagIcon: DefineComponent<{}, {}, any>;
    export const FolderAddIcon: DefineComponent<{}, {}, any>;
    export const FolderDownloadIcon: DefineComponent<{}, {}, any>;
    export const FolderOpenIcon: DefineComponent<{}, {}, any>;
    export const FolderRemoveIcon: DefineComponent<{}, {}, any>;
    export const FolderIcon: DefineComponent<{}, {}, any>;
    export const GiftIcon: DefineComponent<{}, {}, any>;
    export const GlobeAltIcon: DefineComponent<{}, {}, any>;
    export const GlobeIcon: DefineComponent<{}, {}, any>;
    export const HandIcon: DefineComponent<{}, {}, any>;
    export const HashtagIcon: DefineComponent<{}, {}, any>;
    export const HeartIcon: DefineComponent<{}, {}, any>;
    export const HomeIcon: DefineComponent<{}, {}, any>;
    export const IdentificationIcon: DefineComponent<{}, {}, any>;
    export const InboxInIcon: DefineComponent<{}, {}, any>;
    export const InboxIcon: DefineComponent<{}, {}, any>;
    export const InformationCircleIcon: DefineComponent<{}, {}, any>;
    export const KeyIcon: DefineComponent<{}, {}, any>;
    export const LibraryIcon: DefineComponent<{}, {}, any>;
    export const LightBulbIcon: DefineComponent<{}, {}, any>;
    export const LightningBoltIcon: DefineComponent<{}, {}, any>;
    export const LinkIcon: DefineComponent<{}, {}, any>;
    export const LocationMarkerIcon: DefineComponent<{}, {}, any>;
    export const LockClosedIcon: DefineComponent<{}, {}, any>;
    export const LockOpenIcon: DefineComponent<{}, {}, any>;
    export const LoginIcon: DefineComponent<{}, {}, any>;
    export const LogoutIcon: DefineComponent<{}, {}, any>;
    export const MailOpenIcon: DefineComponent<{}, {}, any>;
    export const MailIcon: DefineComponent<{}, {}, any>;
    export const MapIcon: DefineComponent<{}, {}, any>;
    export const MenuAlt1Icon: DefineComponent<{}, {}, any>;
    export const MenuAlt2Icon: DefineComponent<{}, {}, any>;
    export const MenuAlt3Icon: DefineComponent<{}, {}, any>;
    export const MenuAlt4Icon: DefineComponent<{}, {}, any>;
    export const MenuIcon: DefineComponent<{}, {}, any>;
    export const MicrophoneIcon: DefineComponent<{}, {}, any>;
    export const MinusCircleIcon: DefineComponent<{}, {}, any>;
    export const MinusSmIcon: DefineComponent<{}, {}, any>;
    export const MinusIcon: DefineComponent<{}, {}, any>;
    export const MoonIcon: DefineComponent<{}, {}, any>;
    export const MusicNoteIcon: DefineComponent<{}, {}, any>;
    export const NewspaperIcon: DefineComponent<{}, {}, any>;
    export const OfficeBuildingIcon: DefineComponent<{}, {}, any>;
    export const PaperAirplaneIcon: DefineComponent<{}, {}, any>;
    export const PaperClipIcon: DefineComponent<{}, {}, any>;
    export const PauseIcon: DefineComponent<{}, {}, any>;
    export const PencilAltIcon: DefineComponent<{}, {}, any>;
    export const PencilIcon: DefineComponent<{}, {}, any>;
    export const PhoneIncomingIcon: DefineComponent<{}, {}, any>;
    export const PhoneMissedCallIcon: DefineComponent<{}, {}, any>;
    export const PhoneOutgoingIcon: DefineComponent<{}, {}, any>;
    export const PhoneIcon: DefineComponent<{}, {}, any>;
    export const PhotographIcon: DefineComponent<{}, {}, any>;
    export const PlayIcon: DefineComponent<{}, {}, any>;
    export const PlusCircleIcon: DefineComponent<{}, {}, any>;
    export const PlusSmIcon: DefineComponent<{}, {}, any>;
    export const PlusIcon: DefineComponent<{}, {}, any>;
    export const PresentationChartBarIcon: DefineComponent<{}, {}, any>;
    export const PresentationChartLineIcon: DefineComponent<{}, {}, any>;
    export const PrinterIcon: DefineComponent<{}, {}, any>;
    export const PuzzleIcon: DefineComponent<{}, {}, any>;
    export const QrcodeIcon: DefineComponent<{}, {}, any>;
    export const QuestionMarkCircleIcon: DefineComponent<{}, {}, any>;
    export const ReceiptRefundIcon: DefineComponent<{}, {}, any>;
    export const ReceiptTaxIcon: DefineComponent<{}, {}, any>;
    export const RefreshIcon: DefineComponent<{}, {}, any>;
    export const ReplyIcon: DefineComponent<{}, {}, any>;
    export const RewindIcon: DefineComponent<{}, {}, any>;
    export const RssIcon: DefineComponent<{}, {}, any>;
    export const SaveAsIcon: DefineComponent<{}, {}, any>;
    export const SaveIcon: DefineComponent<{}, {}, any>;
    export const ScaleIcon: DefineComponent<{}, {}, any>;
    export const ScissorsIcon: DefineComponent<{}, {}, any>;
    export const SearchCircleIcon: DefineComponent<{}, {}, any>;
    export const SearchIcon: DefineComponent<{}, {}, any>;
    export const SelectorIcon: DefineComponent<{}, {}, any>;
    export const ServerIcon: DefineComponent<{}, {}, any>;
    export const ShareIcon: DefineComponent<{}, {}, any>;
    export const ShieldCheckIcon: DefineComponent<{}, {}, any>;
    export const ShieldExclamationIcon: DefineComponent<{}, {}, any>;
    export const ShoppingBagIcon: DefineComponent<{}, {}, any>;
    export const ShoppingCartIcon: DefineComponent<{}, {}, any>;
    export const SortAscendingIcon: DefineComponent<{}, {}, any>;
    export const SortDescendingIcon: DefineComponent<{}, {}, any>;
    export const SparklesIcon: DefineComponent<{}, {}, any>;
    export const SpeakerphoneIcon: DefineComponent<{}, {}, any>;
    export const StarIcon: DefineComponent<{}, {}, any>;
    export const StatusOfflineIcon: DefineComponent<{}, {}, any>;
    export const StatusOnlineIcon: DefineComponent<{}, {}, any>;
    export const StopIcon: DefineComponent<{}, {}, any>;
    export const SunIcon: DefineComponent<{}, {}, any>;
    export const SupportIcon: DefineComponent<{}, {}, any>;
    export const SwitchHorizontalIcon: DefineComponent<{}, {}, any>;
    export const SwitchVerticalIcon: DefineComponent<{}, {}, any>;
    export const TableIcon: DefineComponent<{}, {}, any>;
    export const TagIcon: DefineComponent<{}, {}, any>;
    export const TemplateIcon: DefineComponent<{}, {}, any>;
    export const TerminalIcon: DefineComponent<{}, {}, any>;
    export const ThumbDownIcon: DefineComponent<{}, {}, any>;
    export const ThumbUpIcon: DefineComponent<{}, {}, any>;
    export const TicketIcon: DefineComponent<{}, {}, any>;
    export const TranslateIcon: DefineComponent<{}, {}, any>;
    export const TrashIcon: DefineComponent<{}, {}, any>;
    export const TrendingDownIcon: DefineComponent<{}, {}, any>;
    export const TrendingUpIcon: DefineComponent<{}, {}, any>;
    export const TruckIcon: DefineComponent<{}, {}, any>;
    export const UploadIcon: DefineComponent<{}, {}, any>;
    export const UserAddIcon: DefineComponent<{}, {}, any>;
    export const UserCircleIcon: DefineComponent<{}, {}, any>;
    export const UserGroupIcon: DefineComponent<{}, {}, any>;
    export const UserRemoveIcon: DefineComponent<{}, {}, any>;
    export const UserIcon: DefineComponent<{}, {}, any>;
    export const UsersIcon: DefineComponent<{}, {}, any>;
    export const VariableIcon: DefineComponent<{}, {}, any>;
    export const VideoCameraIcon: DefineComponent<{}, {}, any>;
    export const ViewBoardsIcon: DefineComponent<{}, {}, any>;
    export const ViewGridAddIcon: DefineComponent<{}, {}, any>;
    export const ViewGridIcon: DefineComponent<{}, {}, any>;
    export const ViewListIcon: DefineComponent<{}, {}, any>;
    export const VolumeOffIcon: DefineComponent<{}, {}, any>;
    export const VolumeUpIcon: DefineComponent<{}, {}, any>;
    export const WifiIcon: DefineComponent<{}, {}, any>;
    export const XCircleIcon: DefineComponent<{}, {}, any>;
    export const XIcon: DefineComponent<{}, {}, any>;
    export const XMarkIcon: DefineComponent<{}, {}, any>;
    export const ZoomInIcon: DefineComponent<{}, {}, any>;
    export const ZoomOutIcon: DefineComponent<{}, {}, any>;
}

declare module "@headlessui/vue" {
    import { DefineComponent } from "vue";

    export const Dialog: DefineComponent;
    export const DialogPanel: DefineComponent;
    export const DialogOverlay: DefineComponent;
    export const DialogTitle: DefineComponent;
    export const DialogDescription: DefineComponent;
    export const Disclosure: DefineComponent;
    export const DisclosureButton: DefineComponent;
    export const DisclosurePanel: DefineComponent;
    export const Listbox: DefineComponent;
    export const ListboxLabel: DefineComponent;
    export const ListboxButton: DefineComponent;
    export const ListboxOptions: DefineComponent;
    export const ListboxOption: DefineComponent;
    export const Menu: DefineComponent;
    export const MenuButton: DefineComponent;
    export const MenuItems: DefineComponent;
    export const MenuItem: DefineComponent;
    export const Popover: DefineComponent;
    export const PopoverButton: DefineComponent;
    export const PopoverPanel: DefineComponent;
    export const Switch: DefineComponent;
    export const SwitchDescription: DefineComponent;
    export const SwitchLabel: DefineComponent;
    export const SwitchGroup: DefineComponent;
    export const Transition: DefineComponent;
    export const TransitionChild: DefineComponent;
    export const TransitionRoot: DefineComponent;
}

declare module "vue-chartjs" {
    import { Component } from "vue";
    export class Bar extends Component {}
    export class Doughnut extends Component {}
    export class Line extends Component {}
    export class Pie extends Component {}
    export class PolarArea extends Component {}
    export class Radar extends Component {}
    export class Bubble extends Component {}
    export class Scatter extends Component {}
    export class mixins extends Component {
        static reactiveProp: any;
    }
  }