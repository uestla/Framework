<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Extension\Diagnostics\HtmlValidator;

use Kdyby;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Nette\Diagnostics\Debugger;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ValidatorPanel extends Nette\Object implements Nette\Diagnostics\IBarPanel
{

	// <editor-fold desc="XML validation constants">
	const XML_ERR_OK = 0;
	const XML_ERR_INTERNAL_ERROR = 1;
	const XML_ERR_NO_MEMORY = 2;
	const XML_ERR_DOCUMENT_START = 3;
	const XML_ERR_DOCUMENT_EMPTY = 4;
	const XML_ERR_DOCUMENT_END = 5;
	const XML_ERR_INVALID_HEX_CHARREF = 6;
	const XML_ERR_INVALID_DEC_CHARREF = 7;
	const XML_ERR_INVALID_CHARREF = 8;
	const XML_ERR_INVALID_CHAR = 9;
	const XML_ERR_CHARREF_AT_EOF = 10;
	const XML_ERR_CHARREF_IN_PROLOG = 11;
	const XML_ERR_CHARREF_IN_EPILOG = 12;
	const XML_ERR_CHARREF_IN_DTD = 13;
	const XML_ERR_ENTITYREF_AT_EOF = 14;
	const XML_ERR_ENTITYREF_IN_PROLOG = 15;
	const XML_ERR_ENTITYREF_IN_EPILOG = 16;
	const XML_ERR_ENTITYREF_IN_DTD = 17;
	const XML_ERR_PEREF_AT_EOF = 18;
	const XML_ERR_PEREF_IN_PROLOG = 19;
	const XML_ERR_PEREF_IN_EPILOG = 20;
	const XML_ERR_PEREF_IN_INT_SUBSET = 21;
	const XML_ERR_ENTITYREF_NO_NAME = 22;
	const XML_ERR_ENTITYREF_SEMICOL_MISSING = 23;
	const XML_ERR_PEREF_NO_NAME = 24;
	const XML_ERR_PEREF_SEMICOL_MISSING = 25;
	const XML_ERR_UNDECLARED_ENTITY = 26;
	const XML_WAR_UNDECLARED_ENTITY = 27;
	const XML_ERR_UNPARSED_ENTITY = 28;
	const XML_ERR_ENTITY_IS_EXTERNAL = 29;
	const XML_ERR_ENTITY_IS_PARAMETER = 30;
	const XML_ERR_UNKNOWN_ENCODING = 31;
	const XML_ERR_UNSUPPORTED_ENCODING = 32;
	const XML_ERR_STRING_NOT_STARTED = 33;
	const XML_ERR_STRING_NOT_CLOSED = 34;
	const XML_ERR_NS_DECL_ERROR = 35;
	const XML_ERR_ENTITY_NOT_STARTED = 36;
	const XML_ERR_ENTITY_NOT_FINISHED = 37;
	const XML_ERR_LT_IN_ATTRIBUTE = 38;
	const XML_ERR_ATTRIBUTE_NOT_STARTED = 39;
	const XML_ERR_ATTRIBUTE_NOT_FINISHED = 40;
	const XML_ERR_ATTRIBUTE_WITHOUT_VALUE = 41;
	const XML_ERR_ATTRIBUTE_REDEFINED = 42;
	const XML_ERR_LITERAL_NOT_STARTED = 43;
	const XML_ERR_LITERAL_NOT_FINISHED = 44;
	const XML_ERR_COMMENT_NOT_FINISHED = 45;
	const XML_ERR_PI_NOT_STARTED = 46;
	const XML_ERR_PI_NOT_FINISHED = 47;
	const XML_ERR_NOTATION_NOT_STARTED = 48;
	const XML_ERR_NOTATION_NOT_FINISHED = 49;
	const XML_ERR_ATTLIST_NOT_STARTED = 50;
	const XML_ERR_ATTLIST_NOT_FINISHED = 51;
	const XML_ERR_MIXED_NOT_STARTED = 52;
	const XML_ERR_MIXED_NOT_FINISHED = 53;
	const XML_ERR_ELEMCONTENT_NOT_STARTED = 54;
	const XML_ERR_ELEMCONTENT_NOT_FINISHED = 55;
	const XML_ERR_XMLDECL_NOT_STARTED = 56;
	const XML_ERR_XMLDECL_NOT_FINISHED = 57;
	const XML_ERR_CONDSEC_NOT_STARTED = 58;
	const XML_ERR_CONDSEC_NOT_FINISHED = 59;
	const XML_ERR_EXT_SUBSET_NOT_FINISHED = 60;
	const XML_ERR_DOCTYPE_NOT_FINISHED = 61;
	const XML_ERR_MISPLACED_CDATA_END = 62;
	const XML_ERR_CDATA_NOT_FINISHED = 63;
	const XML_ERR_RESERVED_XML_NAME = 64;
	const XML_ERR_SPACE_REQUIRED = 65;
	const XML_ERR_SEPARATOR_REQUIRED = 66;
	const XML_ERR_NMTOKEN_REQUIRED = 67;
	const XML_ERR_NAME_REQUIRED = 68;
	const XML_ERR_PCDATA_REQUIRED = 69;
	const XML_ERR_URI_REQUIRED = 70;
	const XML_ERR_PUBID_REQUIRED = 71;
	const XML_ERR_LT_REQUIRED = 72;
	const XML_ERR_GT_REQUIRED = 73;
	const XML_ERR_LTSLASH_REQUIRED = 74;
	const XML_ERR_EQUAL_REQUIRED = 75;
	const XML_ERR_TAG_NAME_MISMATCH = 76;
	const XML_ERR_TAG_NOT_FINISHED = 77;
	const XML_ERR_STANDALONE_VALUE = 78;
	const XML_ERR_ENCODING_NAME = 79;
	const XML_ERR_HYPHEN_IN_COMMENT = 80;
	const XML_ERR_INVALID_ENCODING = 81;
	const XML_ERR_EXT_ENTITY_STANDALONE = 82;
	const XML_ERR_CONDSEC_INVALID = 83;
	const XML_ERR_VALUE_REQUIRED = 84;
	const XML_ERR_NOT_WELL_BALANCED = 85;
	const XML_ERR_EXTRA_CONTENT = 86;
	const XML_ERR_ENTITY_CHAR_ERROR = 87;
	const XML_ERR_ENTITY_PE_INTERNAL = 88;
	const XML_ERR_ENTITY_LOOP = 89;
	const XML_ERR_ENTITY_BOUNDARY = 90;
	const XML_ERR_INVALID_URI = 91;
	const XML_ERR_URI_FRAGMENT = 92;
	const XML_WAR_CATALOG_PI = 93;
	const XML_ERR_NO_DTD = 94;
	const XML_ERR_CONDSEC_INVALID_KEYWORD = 95;
	const XML_ERR_VERSION_MISSING = 96;
	const XML_WAR_UNKNOWN_VERSION = 97;
	const XML_WAR_LANG_VALUE = 98;
	const XML_WAR_NS_URI = 99;
	const XML_WAR_NS_URI_RELATIVE = 100;
	const XML_ERR_MISSING_ENCODING = 101;
	const XML_WAR_SPACE_VALUE = 102;
	const XML_ERR_NOT_STANDALONE = 103;
	const XML_ERR_ENTITY_PROCESSING = 104;
	const XML_ERR_NOTATION_PROCESSING = 105;
	const XML_WAR_NS_COLUMN = 106;
	const XML_WAR_ENTITY_REDEFINED = 107;
	const XML_ERR_UNKNOWN_VERSION = 108;
	const XML_ERR_VERSION_MISMATCH = 109;
	const XML_ERR_NAME_TOO_LONG = 110;
	const XML_NS_ERR_XML_NAMESPACE = 200;
	const XML_NS_ERR_UNDEFINED_NAMESPACE = 201;
	const XML_NS_ERR_QNAME = 202;
	const XML_NS_ERR_ATTRIBUTE_REDEFINED = 203;
	const XML_NS_ERR_EMPTY = 204;
	const XML_NS_ERR_COLON = 205;
	const XML_DTD_ATTRIBUTE_DEFAULT = 500;
	const XML_DTD_ATTRIBUTE_REDEFINED = 501;
	const XML_DTD_ATTRIBUTE_VALUE = 502;
	const XML_DTD_CONTENT_ERROR = 503;
	const XML_DTD_CONTENT_MODEL = 504;
	const XML_DTD_CONTENT_NOT_DETERMINIST = 505;
	const XML_DTD_DIFFERENT_PREFIX = 506;
	const XML_DTD_ELEM_DEFAULT_NAMESPACE = 507;
	const XML_DTD_ELEM_NAMESPACE = 508;
	const XML_DTD_ELEM_REDEFINED = 509;
	const XML_DTD_EMPTY_NOTATION = 510;
	const XML_DTD_ENTITY_TYPE = 511;
	const XML_DTD_ID_FIXED = 512;
	const XML_DTD_ID_REDEFINED = 513;
	const XML_DTD_ID_SUBSET = 514;
	const XML_DTD_INVALID_CHILD = 515;
	const XML_DTD_INVALID_DEFAULT = 516;
	const XML_DTD_LOAD_ERROR = 517;
	const XML_DTD_MISSING_ATTRIBUTE = 518;
	const XML_DTD_MIXED_CORRUPT = 519;
	const XML_DTD_MULTIPLE_ID = 520;
	const XML_DTD_NO_DOC = 521;
	const XML_DTD_NO_DTD = 522;
	const XML_DTD_NO_ELEM_NAME = 523;
	const XML_DTD_NO_PREFIX = 524;
	const XML_DTD_NO_ROOT = 525;
	const XML_DTD_NOTATION_REDEFINED = 526;
	const XML_DTD_NOTATION_VALUE = 527;
	const XML_DTD_NOT_EMPTY = 528;
	const XML_DTD_NOT_PCDATA = 529;
	const XML_DTD_NOT_STANDALONE = 530;
	const XML_DTD_ROOT_NAME = 531;
	const XML_DTD_STANDALONE_WHITE_SPACE = 532;
	const XML_DTD_UNKNOWN_ATTRIBUTE = 533;
	const XML_DTD_UNKNOWN_ELEM = 534;
	const XML_DTD_UNKNOWN_ENTITY = 535;
	const XML_DTD_UNKNOWN_ID = 536;
	const XML_DTD_UNKNOWN_NOTATION = 537;
	const XML_DTD_STANDALONE_DEFAULTED = 538;
	const XML_DTD_XMLID_VALUE = 539;
	const XML_DTD_XMLID_TYPE = 540;
	const XML_DTD_DUP_TOKEN = 541;
	const XML_HTML_STRUCURE_ERROR = 800;
	const XML_HTML_UNKNOWN_TAG = 801;
	const XML_RNGP_ANYNAME_ATTR_ANCESTOR = 1000;
	const XML_RNGP_ATTR_CONFLICT = 1001;
	const XML_RNGP_ATTRIBUTE_CHILDREN = 1002;
	const XML_RNGP_ATTRIBUTE_CONTENT = 1003;
	const XML_RNGP_ATTRIBUTE_EMPTY = 1004;
	const XML_RNGP_ATTRIBUTE_NOOP = 1005;
	const XML_RNGP_CHOICE_CONTENT = 1006;
	const XML_RNGP_CHOICE_EMPTY = 1007;
	const XML_RNGP_CREATE_FAILURE = 1008;
	const XML_RNGP_DATA_CONTENT = 1009;
	const XML_RNGP_DEF_CHOICE_AND_INTERLEAVE = 1010;
	const XML_RNGP_DEFINE_CREATE_FAILED = 1011;
	const XML_RNGP_DEFINE_EMPTY = 1012;
	const XML_RNGP_DEFINE_MISSING = 1013;
	const XML_RNGP_DEFINE_NAME_MISSING = 1014;
	const XML_RNGP_ELEM_CONTENT_EMPTY = 1015;
	const XML_RNGP_ELEM_CONTENT_ERROR = 1016;
	const XML_RNGP_ELEMENT_EMPTY = 1017;
	const XML_RNGP_ELEMENT_CONTENT = 1018;
	const XML_RNGP_ELEMENT_NAME = 1019;
	const XML_RNGP_ELEMENT_NO_CONTENT = 1020;
	const XML_RNGP_ELEM_TEXT_CONFLICT = 1021;
	const XML_RNGP_EMPTY = 1022;
	const XML_RNGP_EMPTY_CONSTRUCT = 1023;
	const XML_RNGP_EMPTY_CONTENT = 1024;
	const XML_RNGP_EMPTY_NOT_EMPTY = 1025;
	const XML_RNGP_ERROR_TYPE_LIB = 1026;
	const XML_RNGP_EXCEPT_EMPTY = 1027;
	const XML_RNGP_EXCEPT_MISSING = 1028;
	const XML_RNGP_EXCEPT_MULTIPLE = 1029;
	const XML_RNGP_EXCEPT_NO_CONTENT = 1030;
	const XML_RNGP_EXTERNALREF_EMTPY = 1031;
	const XML_RNGP_EXTERNAL_REF_FAILURE = 1032;
	const XML_RNGP_EXTERNALREF_RECURSE = 1033;
	const XML_RNGP_FORBIDDEN_ATTRIBUTE = 1034;
	const XML_RNGP_FOREIGN_ELEMENT = 1035;
	const XML_RNGP_GRAMMAR_CONTENT = 1036;
	const XML_RNGP_GRAMMAR_EMPTY = 1037;
	const XML_RNGP_GRAMMAR_MISSING = 1038;
	const XML_RNGP_GRAMMAR_NO_START = 1039;
	const XML_RNGP_GROUP_ATTR_CONFLICT = 1040;
	const XML_RNGP_HREF_ERROR = 1041;
	const XML_RNGP_INCLUDE_EMPTY = 1042;
	const XML_RNGP_INCLUDE_FAILURE = 1043;
	const XML_RNGP_INCLUDE_RECURSE = 1044;
	const XML_RNGP_INTERLEAVE_ADD = 1045;
	const XML_RNGP_INTERLEAVE_CREATE_FAILED = 1046;
	const XML_RNGP_INTERLEAVE_EMPTY = 1047;
	const XML_RNGP_INTERLEAVE_NO_CONTENT = 1048;
	const XML_RNGP_INVALID_DEFINE_NAME = 1049;
	const XML_RNGP_INVALID_URI = 1050;
	const XML_RNGP_INVALID_VALUE = 1051;
	const XML_RNGP_MISSING_HREF = 1052;
	const XML_RNGP_NAME_MISSING = 1053;
	const XML_RNGP_NEED_COMBINE = 1054;
	const XML_RNGP_NOTALLOWED_NOT_EMPTY = 1055;
	const XML_RNGP_NSNAME_ATTR_ANCESTOR = 1056;
	const XML_RNGP_NSNAME_NO_NS = 1057;
	const XML_RNGP_PARAM_FORBIDDEN = 1058;
	const XML_RNGP_PARAM_NAME_MISSING = 1059;
	const XML_RNGP_PARENTREF_CREATE_FAILED = 1060;
	const XML_RNGP_PARENTREF_NAME_INVALID = 1061;
	const XML_RNGP_PARENTREF_NO_NAME = 1062;
	const XML_RNGP_PARENTREF_NO_PARENT = 1063;
	const XML_RNGP_PARENTREF_NOT_EMPTY = 1064;
	const XML_RNGP_PARSE_ERROR = 1065;
	const XML_RNGP_PAT_ANYNAME_EXCEPT_ANYNAME = 1066;
	const XML_RNGP_PAT_ATTR_ATTR = 1067;
	const XML_RNGP_PAT_ATTR_ELEM = 1068;
	const XML_RNGP_PAT_DATA_EXCEPT_ATTR = 1069;
	const XML_RNGP_PAT_DATA_EXCEPT_ELEM = 1070;
	const XML_RNGP_PAT_DATA_EXCEPT_EMPTY = 1071;
	const XML_RNGP_PAT_DATA_EXCEPT_GROUP = 1072;
	const XML_RNGP_PAT_DATA_EXCEPT_INTERLEAVE = 1073;
	const XML_RNGP_PAT_DATA_EXCEPT_LIST = 1074;
	const XML_RNGP_PAT_DATA_EXCEPT_ONEMORE = 1075;
	const XML_RNGP_PAT_DATA_EXCEPT_REF = 1076;
	const XML_RNGP_PAT_DATA_EXCEPT_TEXT = 1077;
	const XML_RNGP_PAT_LIST_ATTR = 1078;
	const XML_RNGP_PAT_LIST_ELEM = 1079;
	const XML_RNGP_PAT_LIST_INTERLEAVE = 1080;
	const XML_RNGP_PAT_LIST_LIST = 1081;
	const XML_RNGP_PAT_LIST_REF = 1082;
	const XML_RNGP_PAT_LIST_TEXT = 1083;
	const XML_RNGP_PAT_NSNAME_EXCEPT_ANYNAME = 1084;
	const XML_RNGP_PAT_NSNAME_EXCEPT_NSNAME = 1085;
	const XML_RNGP_PAT_ONEMORE_GROUP_ATTR = 1086;
	const XML_RNGP_PAT_ONEMORE_INTERLEAVE_ATTR = 1087;
	const XML_RNGP_PAT_START_ATTR = 1088;
	const XML_RNGP_PAT_START_DATA = 1089;
	const XML_RNGP_PAT_START_EMPTY = 1090;
	const XML_RNGP_PAT_START_GROUP = 1091;
	const XML_RNGP_PAT_START_INTERLEAVE = 1092;
	const XML_RNGP_PAT_START_LIST = 1093;
	const XML_RNGP_PAT_START_ONEMORE = 1094;
	const XML_RNGP_PAT_START_TEXT = 1095;
	const XML_RNGP_PAT_START_VALUE = 1096;
	const XML_RNGP_PREFIX_UNDEFINED = 1097;
	const XML_RNGP_REF_CREATE_FAILED = 1098;
	const XML_RNGP_REF_CYCLE = 1099;
	const XML_RNGP_REF_NAME_INVALID = 1100;
	const XML_RNGP_REF_NO_DEF = 1101;
	const XML_RNGP_REF_NO_NAME = 1102;
	const XML_RNGP_REF_NOT_EMPTY = 1103;
	const XML_RNGP_START_CHOICE_AND_INTERLEAVE = 1104;
	const XML_RNGP_START_CONTENT = 1105;
	const XML_RNGP_START_EMPTY = 1106;
	const XML_RNGP_START_MISSING = 1107;
	const XML_RNGP_TEXT_EXPECTED = 1108;
	const XML_RNGP_TEXT_HAS_CHILD = 1109;
	const XML_RNGP_TYPE_MISSING = 1110;
	const XML_RNGP_TYPE_NOT_FOUND = 1111;
	const XML_RNGP_TYPE_VALUE = 1112;
	const XML_RNGP_UNKNOWN_ATTRIBUTE = 1113;
	const XML_RNGP_UNKNOWN_COMBINE = 1114;
	const XML_RNGP_UNKNOWN_CONSTRUCT = 1115;
	const XML_RNGP_UNKNOWN_TYPE_LIB = 1116;
	const XML_RNGP_URI_FRAGMENT = 1117;
	const XML_RNGP_URI_NOT_ABSOLUTE = 1118;
	const XML_RNGP_VALUE_EMPTY = 1119;
	const XML_RNGP_VALUE_NO_CONTENT = 1120;
	const XML_RNGP_XMLNS_NAME = 1121;
	const XML_RNGP_XML_NS = 1122;
	const XML_XPATH_EXPRESSION_OK = 1200;
	const XML_XPATH_NUMBER_ERROR = 1201;
	const XML_XPATH_UNFINISHED_LITERAL_ERROR = 1202;
	const XML_XPATH_START_LITERAL_ERROR = 1203;
	const XML_XPATH_VARIABLE_REF_ERROR = 1204;
	const XML_XPATH_UNDEF_VARIABLE_ERROR = 1205;
	const XML_XPATH_INVALID_PREDICATE_ERROR = 1206;
	const XML_XPATH_EXPR_ERROR = 1207;
	const XML_XPATH_UNCLOSED_ERROR = 1208;
	const XML_XPATH_UNKNOWN_FUNC_ERROR = 1209;
	const XML_XPATH_INVALID_OPERAND = 1210;
	const XML_XPATH_INVALID_TYPE = 1211;
	const XML_XPATH_INVALID_ARITY = 1212;
	const XML_XPATH_INVALID_CTXT_SIZE = 1213;
	const XML_XPATH_INVALID_CTXT_POSITION = 1214;
	const XML_XPATH_MEMORY_ERROR = 1215;
	const XML_XPTR_SYNTAX_ERROR = 1216;
	const XML_XPTR_RESOURCE_ERROR = 1217;
	const XML_XPTR_SUB_RESOURCE_ERROR = 1218;
	const XML_XPATH_UNDEF_PREFIX_ERROR = 1219;
	const XML_XPATH_ENCODING_ERROR = 1220;
	const XML_XPATH_INVALID_CHAR_ERROR = 1221;
	const XML_TREE_INVALID_HEX = 1300;
	const XML_TREE_INVALID_DEC = 1301;
	const XML_TREE_UNTERMINATED_ENTITY = 1302;
	const XML_TREE_NOT_UTF8 = 1303;
	const XML_SAVE_NOT_UTF8 = 1400;
	const XML_SAVE_CHAR_INVALID = 1401;
	const XML_SAVE_NO_DOCTYPE = 1402;
	const XML_SAVE_UNKNOWN_ENCODING = 1403;
	const XML_REGEXP_COMPILE_ERROR = 1450;
	const XML_IO_UNKNOWN = 1500;
	const XML_IO_EACCES = 1501;
	const XML_IO_EAGAIN = 1502;
	const XML_IO_EBADF = 1503;
	const XML_IO_EBADMSG = 1504;
	const XML_IO_EBUSY = 1505;
	const XML_IO_ECANCELED = 1506;
	const XML_IO_ECHILD = 1507;
	const XML_IO_EDEADLK = 1508;
	const XML_IO_EDOM = 1509;
	const XML_IO_EEXIST = 1510;
	const XML_IO_EFAULT = 1511;
	const XML_IO_EFBIG = 1512;
	const XML_IO_EINPROGRESS = 1513;
	const XML_IO_EINTR = 1514;
	const XML_IO_EINVAL = 1515;
	const XML_IO_EIO = 1516;
	const XML_IO_EISDIR = 1517;
	const XML_IO_EMFILE = 1518;
	const XML_IO_EMLINK = 1519;
	const XML_IO_EMSGSIZE = 1520;
	const XML_IO_ENAMETOOLONG = 1521;
	const XML_IO_ENFILE = 1522;
	const XML_IO_ENODEV = 1523;
	const XML_IO_ENOENT = 1524;
	const XML_IO_ENOEXEC = 1525;
	const XML_IO_ENOLCK = 1526;
	const XML_IO_ENOMEM = 1527;
	const XML_IO_ENOSPC = 1528;
	const XML_IO_ENOSYS = 1529;
	const XML_IO_ENOTDIR = 1530;
	const XML_IO_ENOTEMPTY = 1531;
	const XML_IO_ENOTSUP = 1532;
	const XML_IO_ENOTTY = 1533;
	const XML_IO_ENXIO = 1534;
	const XML_IO_EPERM = 1535;
	const XML_IO_EPIPE = 1536;
	const XML_IO_ERANGE = 1537;
	const XML_IO_EROFS = 1538;
	const XML_IO_ESPIPE = 1539;
	const XML_IO_ESRCH = 1540;
	const XML_IO_ETIMEDOUT = 1541;
	const XML_IO_EXDEV = 1542;
	const XML_IO_NETWORK_ATTEMPT = 1543;
	const XML_IO_ENCODER = 1544;
	const XML_IO_FLUSH = 1545;
	const XML_IO_WRITE = 1546;
	const XML_IO_NO_INPUT = 1547;
	const XML_IO_BUFFER_FULL = 1548;
	const XML_IO_LOAD_ERROR = 1549;
	const XML_IO_ENOTSOCK = 1550;
	const XML_IO_EISCONN = 1551;
	const XML_IO_ECONNREFUSED = 1552;
	const XML_IO_ENETUNREACH = 1553;
	const XML_IO_EADDRINUSE = 1554;
	const XML_IO_EALREADY = 1555;
	const XML_IO_EAFNOSUPPORT = 1556;
	const XML_XINCLUDE_RECURSION = 1600;
	const XML_XINCLUDE_PARSE_VALUE = 1601;
	const XML_XINCLUDE_ENTITY_DEF_MISMATCH = 1602;
	const XML_XINCLUDE_NO_HREF = 1603;
	const XML_XINCLUDE_NO_FALLBACK = 1604;
	const XML_XINCLUDE_HREF_URI = 1605;
	const XML_XINCLUDE_TEXT_FRAGMENT = 1606;
	const XML_XINCLUDE_TEXT_DOCUMENT = 1607;
	const XML_XINCLUDE_INVALID_CHAR = 1608;
	const XML_XINCLUDE_BUILD_FAILED = 1609;
	const XML_XINCLUDE_UNKNOWN_ENCODING = 1610;
	const XML_XINCLUDE_MULTIPLE_ROOT = 1611;
	const XML_XINCLUDE_XPTR_FAILED = 1612;
	const XML_XINCLUDE_XPTR_RESULT = 1613;
	const XML_XINCLUDE_INCLUDE_IN_INCLUDE = 1614;
	const XML_XINCLUDE_FALLBACKS_IN_INCLUDE = 1615;
	const XML_XINCLUDE_FALLBACK_NOT_IN_INCLUDE = 1616;
	const XML_XINCLUDE_DEPRECATED_NS = 1617;
	const XML_XINCLUDE_FRAGMENT_ID = 1618;
	const XML_CATALOG_MISSING_ATTR = 1650;
	const XML_CATALOG_ENTRY_BROKEN = 1651;
	const XML_CATALOG_PREFER_VALUE = 1652;
	const XML_CATALOG_NOT_CATALOG = 1653;
	const XML_CATALOG_RECURSION = 1654;
	const XML_SCHEMAP_PREFIX_UNDEFINED = 1700;
	const XML_SCHEMAP_ATTRFORMDEFAULT_VALUE = 1701;
	const XML_SCHEMAP_ATTRGRP_NONAME_NOREF = 1702;
	const XML_SCHEMAP_ATTR_NONAME_NOREF = 1703;
	const XML_SCHEMAP_COMPLEXTYPE_NONAME_NOREF = 1704;
	const XML_SCHEMAP_ELEMFORMDEFAULT_VALUE = 1705;
	const XML_SCHEMAP_ELEM_NONAME_NOREF = 1706;
	const XML_SCHEMAP_EXTENSION_NO_BASE = 1707;
	const XML_SCHEMAP_FACET_NO_VALUE = 1708;
	const XML_SCHEMAP_FAILED_BUILD_IMPORT = 1709;
	const XML_SCHEMAP_GROUP_NONAME_NOREF = 1710;
	const XML_SCHEMAP_IMPORT_NAMESPACE_NOT_URI = 1711;
	const XML_SCHEMAP_IMPORT_REDEFINE_NSNAME = 1712;
	const XML_SCHEMAP_IMPORT_SCHEMA_NOT_URI = 1713;
	const XML_SCHEMAP_INVALID_BOOLEAN = 1714;
	const XML_SCHEMAP_INVALID_ENUM = 1715;
	const XML_SCHEMAP_INVALID_FACET = 1716;
	const XML_SCHEMAP_INVALID_FACET_VALUE = 1717;
	const XML_SCHEMAP_INVALID_MAXOCCURS = 1718;
	const XML_SCHEMAP_INVALID_MINOCCURS = 1719;
	const XML_SCHEMAP_INVALID_REF_AND_SUBTYPE = 1720;
	const XML_SCHEMAP_INVALID_WHITE_SPACE = 1721;
	const XML_SCHEMAP_NOATTR_NOREF = 1722;
	const XML_SCHEMAP_NOTATION_NO_NAME = 1723;
	const XML_SCHEMAP_NOTYPE_NOREF = 1724;
	const XML_SCHEMAP_REF_AND_SUBTYPE = 1725;
	const XML_SCHEMAP_RESTRICTION_NONAME_NOREF = 1726;
	const XML_SCHEMAP_SIMPLETYPE_NONAME = 1727;
	const XML_SCHEMAP_TYPE_AND_SUBTYPE = 1728;
	const XML_SCHEMAP_UNKNOWN_ALL_CHILD = 1729;
	const XML_SCHEMAP_UNKNOWN_ANYATTRIBUTE_CHILD = 1730;
	const XML_SCHEMAP_UNKNOWN_ATTR_CHILD = 1731;
	const XML_SCHEMAP_UNKNOWN_ATTRGRP_CHILD = 1732;
	const XML_SCHEMAP_UNKNOWN_ATTRIBUTE_GROUP = 1733;
	const XML_SCHEMAP_UNKNOWN_BASE_TYPE = 1734;
	const XML_SCHEMAP_UNKNOWN_CHOICE_CHILD = 1735;
	const XML_SCHEMAP_UNKNOWN_COMPLEXCONTENT_CHILD = 1736;
	const XML_SCHEMAP_UNKNOWN_COMPLEXTYPE_CHILD = 1737;
	const XML_SCHEMAP_UNKNOWN_ELEM_CHILD = 1738;
	const XML_SCHEMAP_UNKNOWN_EXTENSION_CHILD = 1739;
	const XML_SCHEMAP_UNKNOWN_FACET_CHILD = 1740;
	const XML_SCHEMAP_UNKNOWN_FACET_TYPE = 1741;
	const XML_SCHEMAP_UNKNOWN_GROUP_CHILD = 1742;
	const XML_SCHEMAP_UNKNOWN_IMPORT_CHILD = 1743;
	const XML_SCHEMAP_UNKNOWN_LIST_CHILD = 1744;
	const XML_SCHEMAP_UNKNOWN_NOTATION_CHILD = 1745;
	const XML_SCHEMAP_UNKNOWN_PROCESSCONTENT_CHILD = 1746;
	const XML_SCHEMAP_UNKNOWN_REF = 1747;
	const XML_SCHEMAP_UNKNOWN_RESTRICTION_CHILD = 1748;
	const XML_SCHEMAP_UNKNOWN_SCHEMAS_CHILD = 1749;
	const XML_SCHEMAP_UNKNOWN_SEQUENCE_CHILD = 1750;
	const XML_SCHEMAP_UNKNOWN_SIMPLECONTENT_CHILD = 1751;
	const XML_SCHEMAP_UNKNOWN_SIMPLETYPE_CHILD = 1752;
	const XML_SCHEMAP_UNKNOWN_TYPE = 1753;
	const XML_SCHEMAP_UNKNOWN_UNION_CHILD = 1754;
	const XML_SCHEMAP_ELEM_DEFAULT_FIXED = 1755;
	const XML_SCHEMAP_REGEXP_INVALID = 1756;
	const XML_SCHEMAP_FAILED_LOAD = 1757;
	const XML_SCHEMAP_NOTHING_TO_PARSE = 1758;
	const XML_SCHEMAP_NOROOT = 1759;
	const XML_SCHEMAP_REDEFINED_GROUP = 1760;
	const XML_SCHEMAP_REDEFINED_TYPE = 1761;
	const XML_SCHEMAP_REDEFINED_ELEMENT = 1762;
	const XML_SCHEMAP_REDEFINED_ATTRGROUP = 1763;
	const XML_SCHEMAP_REDEFINED_ATTR = 1764;
	const XML_SCHEMAP_REDEFINED_NOTATION = 1765;
	const XML_SCHEMAP_FAILED_PARSE = 1766;
	const XML_SCHEMAP_UNKNOWN_PREFIX = 1767;
	const XML_SCHEMAP_DEF_AND_PREFIX = 1768;
	const XML_SCHEMAP_UNKNOWN_INCLUDE_CHILD = 1769;
	const XML_SCHEMAP_INCLUDE_SCHEMA_NOT_URI = 1770;
	const XML_SCHEMAP_INCLUDE_SCHEMA_NO_URI = 1771;
	const XML_SCHEMAP_NOT_SCHEMA = 1772;
	const XML_SCHEMAP_UNKNOWN_MEMBER_TYPE = 1773;
	const XML_SCHEMAP_INVALID_ATTR_USE = 1774;
	const XML_SCHEMAP_RECURSIVE = 1775;
	const XML_SCHEMAP_SUPERNUMEROUS_LIST_ITEM_TYPE = 1776;
	const XML_SCHEMAP_INVALID_ATTR_COMBINATION = 1777;
	const XML_SCHEMAP_INVALID_ATTR_INLINE_COMBINATION = 1778;
	const XML_SCHEMAP_MISSING_SIMPLETYPE_CHILD = 1779;
	const XML_SCHEMAP_INVALID_ATTR_NAME = 1780;
	const XML_SCHEMAP_REF_AND_CONTENT = 1781;
	const XML_SCHEMAP_CT_PROPS_CORRECT_1 = 1782;
	const XML_SCHEMAP_CT_PROPS_CORRECT_2 = 1783;
	const XML_SCHEMAP_CT_PROPS_CORRECT_3 = 1784;
	const XML_SCHEMAP_CT_PROPS_CORRECT_4 = 1785;
	const XML_SCHEMAP_CT_PROPS_CORRECT_5 = 1786;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_1 = 1787;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_1 = 1788;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_2 = 1789;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_2 = 1790;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_3 = 1791;
	const XML_SCHEMAP_WILDCARD_INVALID_NS_MEMBER = 1792;
	const XML_SCHEMAP_INTERSECTION_NOT_EXPRESSIBLE = 1793;
	const XML_SCHEMAP_UNION_NOT_EXPRESSIBLE = 1794;
	const XML_SCHEMAP_SRC_IMPORT_3_1 = 1795;
	const XML_SCHEMAP_SRC_IMPORT_3_2 = 1796;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_1 = 1797;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_2 = 1798;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_3 = 1799;
	const XML_SCHEMAP_COS_CT_EXTENDS_1_3 = 1800;
	const XML_SCHEMAV_NOROOT = 1801;
	const XML_SCHEMAV_UNDECLAREDELEM = 1802;
	const XML_SCHEMAV_NOTTOPLEVEL = 1803;
	const XML_SCHEMAV_MISSING = 1804;
	const XML_SCHEMAV_WRONGELEM = 1805;
	const XML_SCHEMAV_NOTYPE = 1806;
	const XML_SCHEMAV_NOROLLBACK = 1807;
	const XML_SCHEMAV_ISABSTRACT = 1808;
	const XML_SCHEMAV_NOTEMPTY = 1809;
	const XML_SCHEMAV_ELEMCONT = 1810;
	const XML_SCHEMAV_HAVEDEFAULT = 1811;
	const XML_SCHEMAV_NOTNILLABLE = 1812;
	const XML_SCHEMAV_EXTRACONTENT = 1813;
	const XML_SCHEMAV_INVALIDATTR = 1814;
	const XML_SCHEMAV_INVALIDELEM = 1815;
	const XML_SCHEMAV_NOTDETERMINIST = 1816;
	const XML_SCHEMAV_CONSTRUCT = 1817;
	const XML_SCHEMAV_INTERNAL = 1818;
	const XML_SCHEMAV_NOTSIMPLE = 1819;
	const XML_SCHEMAV_ATTRUNKNOWN = 1820;
	const XML_SCHEMAV_ATTRINVALID = 1821;
	const XML_SCHEMAV_VALUE = 1822;
	const XML_SCHEMAV_FACET = 1823;
	const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_1 = 1824;
	const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_2 = 1825;
	const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_3 = 1826;
	const XML_SCHEMAV_CVC_TYPE_3_1_1 = 1827;
	const XML_SCHEMAV_CVC_TYPE_3_1_2 = 1828;
	const XML_SCHEMAV_CVC_FACET_VALID = 1829;
	const XML_SCHEMAV_CVC_LENGTH_VALID = 1830;
	const XML_SCHEMAV_CVC_MINLENGTH_VALID = 1831;
	const XML_SCHEMAV_CVC_MAXLENGTH_VALID = 1832;
	const XML_SCHEMAV_CVC_MININCLUSIVE_VALID = 1833;
	const XML_SCHEMAV_CVC_MAXINCLUSIVE_VALID = 1834;
	const XML_SCHEMAV_CVC_MINEXCLUSIVE_VALID = 1835;
	const XML_SCHEMAV_CVC_MAXEXCLUSIVE_VALID = 1836;
	const XML_SCHEMAV_CVC_TOTALDIGITS_VALID = 1837;
	const XML_SCHEMAV_CVC_FRACTIONDIGITS_VALID = 1838;
	const XML_SCHEMAV_CVC_PATTERN_VALID = 1839;
	const XML_SCHEMAV_CVC_ENUMERATION_VALID = 1840;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_1 = 1841;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_2 = 1842;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_3 = 1843;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_4 = 1844;
	const XML_SCHEMAV_CVC_ELT_1 = 1845;
	const XML_SCHEMAV_CVC_ELT_2 = 1846;
	const XML_SCHEMAV_CVC_ELT_3_1 = 1847;
	const XML_SCHEMAV_CVC_ELT_3_2_1 = 1848;
	const XML_SCHEMAV_CVC_ELT_3_2_2 = 1849;
	const XML_SCHEMAV_CVC_ELT_4_1 = 1850;
	const XML_SCHEMAV_CVC_ELT_4_2 = 1851;
	const XML_SCHEMAV_CVC_ELT_4_3 = 1852;
	const XML_SCHEMAV_CVC_ELT_5_1_1 = 1853;
	const XML_SCHEMAV_CVC_ELT_5_1_2 = 1854;
	const XML_SCHEMAV_CVC_ELT_5_2_1 = 1855;
	const XML_SCHEMAV_CVC_ELT_5_2_2_1 = 1856;
	const XML_SCHEMAV_CVC_ELT_5_2_2_2_1 = 1857;
	const XML_SCHEMAV_CVC_ELT_5_2_2_2_2 = 1858;
	const XML_SCHEMAV_CVC_ELT_6 = 1859;
	const XML_SCHEMAV_CVC_ELT_7 = 1860;
	const XML_SCHEMAV_CVC_ATTRIBUTE_1 = 1861;
	const XML_SCHEMAV_CVC_ATTRIBUTE_2 = 1862;
	const XML_SCHEMAV_CVC_ATTRIBUTE_3 = 1863;
	const XML_SCHEMAV_CVC_ATTRIBUTE_4 = 1864;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_1 = 1865;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_2_1 = 1866;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_2_2 = 1867;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_4 = 1868;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_5_1 = 1869;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_5_2 = 1870;
	const XML_SCHEMAV_ELEMENT_CONTENT = 1871;
	const XML_SCHEMAV_DOCUMENT_ELEMENT_MISSING = 1872;
	const XML_SCHEMAV_CVC_COMPLEX_TYPE_1 = 1873;
	const XML_SCHEMAV_CVC_AU = 1874;
	const XML_SCHEMAV_CVC_TYPE_1 = 1875;
	const XML_SCHEMAV_CVC_TYPE_2 = 1876;
	const XML_SCHEMAV_CVC_IDC = 1877;
	const XML_SCHEMAV_CVC_WILDCARD = 1878;
	const XML_SCHEMAV_MISC = 1879;
	const XML_XPTR_UNKNOWN_SCHEME = 1900;
	const XML_XPTR_CHILDSEQ_START = 1901;
	const XML_XPTR_EVAL_FAILED = 1902;
	const XML_XPTR_EXTRA_OBJECTS = 1903;
	const XML_C14N_CREATE_CTXT = 1950;
	const XML_C14N_REQUIRES_UTF8 = 1951;
	const XML_C14N_CREATE_STACK = 1952;
	const XML_C14N_INVALID_NODE = 1953;
	const XML_C14N_UNKNOW_NODE = 1954;
	const XML_C14N_RELATIVE_NAMESPACE = 1955;
	const XML_FTP_PASV_ANSWER = 2000;
	const XML_FTP_EPSV_ANSWER = 2001;
	const XML_FTP_ACCNT = 2002;
	const XML_FTP_URL_SYNTAX = 2003;
	const XML_HTTP_URL_SYNTAX = 2020;
	const XML_HTTP_USE_IP = 2021;
	const XML_HTTP_UNKNOWN_HOST = 2022;
	const XML_SCHEMAP_SRC_SIMPLE_TYPE_1 = 3000;
	const XML_SCHEMAP_SRC_SIMPLE_TYPE_2 = 3001;
	const XML_SCHEMAP_SRC_SIMPLE_TYPE_3 = 3002;
	const XML_SCHEMAP_SRC_SIMPLE_TYPE_4 = 3003;
	const XML_SCHEMAP_SRC_RESOLVE = 3004;
	const XML_SCHEMAP_SRC_RESTRICTION_BASE_OR_SIMPLETYPE = 3005;
	const XML_SCHEMAP_SRC_LIST_ITEMTYPE_OR_SIMPLETYPE = 3006;
	const XML_SCHEMAP_SRC_UNION_MEMBERTYPES_OR_SIMPLETYPES = 3007;
	const XML_SCHEMAP_ST_PROPS_CORRECT_1 = 3008;
	const XML_SCHEMAP_ST_PROPS_CORRECT_2 = 3009;
	const XML_SCHEMAP_ST_PROPS_CORRECT_3 = 3010;
	const XML_SCHEMAP_COS_ST_RESTRICTS_1_1 = 3011;
	const XML_SCHEMAP_COS_ST_RESTRICTS_1_2 = 3012;
	const XML_SCHEMAP_COS_ST_RESTRICTS_1_3_1 = 3013;
	const XML_SCHEMAP_COS_ST_RESTRICTS_1_3_2 = 3014;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_1 = 3015;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_1_1 = 3016;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_1_2 = 3017;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_1 = 3018;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_2 = 3019;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_3 = 3020;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_4 = 3021;
	const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_5 = 3022;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_1 = 3023;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_1 = 3024;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_1_2 = 3025;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_2 = 3026;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_1 = 3027;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_3 = 3028;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_4 = 3029;
	const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_5 = 3030;
	const XML_SCHEMAP_COS_ST_DERIVED_OK_2_1 = 3031;
	const XML_SCHEMAP_COS_ST_DERIVED_OK_2_2 = 3032;
	const XML_SCHEMAP_S4S_ELEM_NOT_ALLOWED = 3033;
	const XML_SCHEMAP_S4S_ELEM_MISSING = 3034;
	const XML_SCHEMAP_S4S_ATTR_NOT_ALLOWED = 3035;
	const XML_SCHEMAP_S4S_ATTR_MISSING = 3036;
	const XML_SCHEMAP_S4S_ATTR_INVALID_VALUE = 3037;
	const XML_SCHEMAP_SRC_ELEMENT_1 = 3038;
	const XML_SCHEMAP_SRC_ELEMENT_2_1 = 3039;
	const XML_SCHEMAP_SRC_ELEMENT_2_2 = 3040;
	const XML_SCHEMAP_SRC_ELEMENT_3 = 3041;
	const XML_SCHEMAP_P_PROPS_CORRECT_1 = 3042;
	const XML_SCHEMAP_P_PROPS_CORRECT_2_1 = 3043;
	const XML_SCHEMAP_P_PROPS_CORRECT_2_2 = 3044;
	const XML_SCHEMAP_E_PROPS_CORRECT_2 = 3045;
	const XML_SCHEMAP_E_PROPS_CORRECT_3 = 3046;
	const XML_SCHEMAP_E_PROPS_CORRECT_4 = 3047;
	const XML_SCHEMAP_E_PROPS_CORRECT_5 = 3048;
	const XML_SCHEMAP_E_PROPS_CORRECT_6 = 3049;
	const XML_SCHEMAP_SRC_INCLUDE = 3050;
	const XML_SCHEMAP_SRC_ATTRIBUTE_1 = 3051;
	const XML_SCHEMAP_SRC_ATTRIBUTE_2 = 3052;
	const XML_SCHEMAP_SRC_ATTRIBUTE_3_1 = 3053;
	const XML_SCHEMAP_SRC_ATTRIBUTE_3_2 = 3054;
	const XML_SCHEMAP_SRC_ATTRIBUTE_4 = 3055;
	const XML_SCHEMAP_NO_XMLNS = 3056;
	const XML_SCHEMAP_NO_XSI = 3057;
	const XML_SCHEMAP_COS_VALID_DEFAULT_1 = 3058;
	const XML_SCHEMAP_COS_VALID_DEFAULT_2_1 = 3059;
	const XML_SCHEMAP_COS_VALID_DEFAULT_2_2_1 = 3060;
	const XML_SCHEMAP_COS_VALID_DEFAULT_2_2_2 = 3061;
	const XML_SCHEMAP_CVC_SIMPLE_TYPE = 3062;
	const XML_SCHEMAP_COS_CT_EXTENDS_1_1 = 3063;
	const XML_SCHEMAP_SRC_IMPORT_1_1 = 3064;
	const XML_SCHEMAP_SRC_IMPORT_1_2 = 3065;
	const XML_SCHEMAP_SRC_IMPORT_2 = 3066;
	const XML_SCHEMAP_SRC_IMPORT_2_1 = 3067;
	const XML_SCHEMAP_SRC_IMPORT_2_2 = 3068;
	const XML_SCHEMAP_INTERNAL = 3069; // non-W3C
	const XML_SCHEMAP_NOT_DETERMINISTIC = 3070; // non-W3C
	const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_1 = 3071;
	const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_2 = 3072;
	const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_3 = 3073;
	const XML_SCHEMAP_MG_PROPS_CORRECT_1 = 3074;
	const XML_SCHEMAP_MG_PROPS_CORRECT_2 = 3075;
	const XML_SCHEMAP_SRC_CT_1 = 3076;
	const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_3 = 3077;
	const XML_SCHEMAP_AU_PROPS_CORRECT_2 = 3078;
	const XML_SCHEMAP_A_PROPS_CORRECT_2 = 3079;
	const XML_SCHEMAP_C_PROPS_CORRECT = 3080;
	const XML_SCHEMAP_SRC_REDEFINE = 3081;
	const XML_SCHEMAP_SRC_IMPORT = 3082;
	const XML_SCHEMAP_WARN_SKIP_SCHEMA = 3083;
	const XML_SCHEMAP_WARN_UNLOCATED_SCHEMA = 3084;
	const XML_SCHEMAP_WARN_ATTR_REDECL_PROH = 3085;
	const XML_SCHEMAP_WARN_ATTR_POINTLESS_PROH = 3086;
	const XML_SCHEMAP_AG_PROPS_CORRECT = 3087;
	const XML_SCHEMAP_COS_CT_EXTENDS_1_2 = 3088;
	const XML_SCHEMAP_AU_PROPS_CORRECT = 3089;
	const XML_SCHEMAP_A_PROPS_CORRECT_3 = 3090;
	const XML_SCHEMAP_COS_ALL_LIMITED = 3091;
	const XML_SCHEMATRONV_ASSERT = 4000;
	const XML_SCHEMATRONV_REPORT = 4001;
	const XML_MODULE_OPEN = 4900;
	const XML_MODULE_CLOSE = 4901;
	const XML_CHECK_FOUND_ELEMENT = 5000;
	const XML_CHECK_FOUND_ATTRIBUTE = 5001;
	const XML_CHECK_FOUND_TEXT = 5002;
	const XML_CHECK_FOUND_CDATA = 5003;
	const XML_CHECK_FOUND_ENTITYREF = 5004;
	const XML_CHECK_FOUND_ENTITY = 5005;
	const XML_CHECK_FOUND_PI = 5006;
	const XML_CHECK_FOUND_COMMENT = 5007;
	const XML_CHECK_FOUND_DOCTYPE = 5008;
	const XML_CHECK_FOUND_FRAGMENT = 5009;
	const XML_CHECK_FOUND_NOTATION = 5010;
	const XML_CHECK_UNKNOWN_NODE = 5011;
	const XML_CHECK_ENTITY_TYPE = 5012;
	const XML_CHECK_NO_PARENT = 5013;
	const XML_CHECK_NO_DOC = 5014;
	const XML_CHECK_NO_NAME = 5015;
	const XML_CHECK_NO_ELEM = 5016;
	const XML_CHECK_WRONG_DOC = 5017;
	const XML_CHECK_NO_PREV = 5018;
	const XML_CHECK_WRONG_PREV = 5019;
	const XML_CHECK_NO_NEXT = 5020;
	const XML_CHECK_WRONG_NEXT = 5021;
	const XML_CHECK_NOT_DTD = 5022;
	const XML_CHECK_NOT_ATTR = 5023;
	const XML_CHECK_NOT_ATTR_DECL = 5024;
	const XML_CHECK_NOT_ELEM_DECL = 5025;
	const XML_CHECK_NOT_ENTITY_DECL = 5026;
	const XML_CHECK_NOT_NS_DECL = 5027;
	const XML_CHECK_NO_HREF = 5028;
	const XML_CHECK_WRONG_PARENT = 5029;
	const XML_CHECK_NS_SCOPE = 5030;
	const XML_CHECK_NS_ANCESTOR = 5031;
	const XML_CHECK_NOT_UTF8 = 5032;
	const XML_CHECK_NO_DICT = 5033;
	const XML_CHECK_NOT_NCNAME = 5034;
	const XML_CHECK_OUTSIDE_DICT = 5035;
	const XML_CHECK_WRONG_NAME = 5036;
	const XML_CHECK_NAME_NOT_NULL = 5037;
	const XML_I18N_NO_NAME = 6000;
	const XML_I18N_NO_HANDLER = 6001;
	const XML_I18N_EXCESS_HANDLER = 6002;
	const XML_I18N_CONV_FAILED = 6003;
	const XML_I18N_NO_OUTPUT = 6004;
	const XML_BUF_OVERFLOW = 7000;
	// </editor-fold>

	/**
	 * @var string
	 */
	private $html;

	/**
	 * @var array|\LibXMLError[]
	 */
	private $errors = array();

	/**
	 * @var array
	 */
	public static $ignoreErrors = array(
		self::XML_HTML_UNKNOWN_TAG
	);

	/**
	 * @var array
	 */
	public static $severenity = array(
		LIBXML_ERR_WARNING => 'Warning',
		LIBXML_ERR_ERROR => 'Error',
		LIBXML_ERR_FATAL => 'Fatal error',
	);



	/**
	 * Renders HTML code for custom tab.
	 * @return string
	 */
	public function getTab()
	{
		$data = callback('Nette\Templating\Helpers::dataStream');
		$img = Html::el('img')->src($data(file_get_contents(__DIR__ . '/icon.png')))->height('16px');
		return $img . ($this->errors ? '<strong style="color:red;font-weight:bold">' . count($this->errors) . ' problems</strong>' : 'Ok');
	}



	/**
	 * Renders HTML code for custom panel.
	 * @return string
	 */
	public function getPanel()
	{
		if (!$this->errors) {
			return NULL;
		}

		ob_start();
		Nette\Utils\LimitedScope::load(__DIR__ . '/panel.phtml', array(
			'errors' => $this->errors,
			'html' => $this->html
		));
		return ob_get_clean();
	}



	/**
	 * Start buffering.
	 */
	public function startBuffering()
	{
		ob_start();
	}



	/**
	 * Stop buffering.
	 */
	public function stopBuffering()
	{
		@ob_end_flush();
	}



	/**
	 * Validate.
	 */
	public function validate()
	{
		$this->html = Strings::normalize(ob_get_contents());
		ob_end_flush();

		libxml_use_internal_errors(true);
		$dom = new \DOMDocument('1.0', 'UTF-8');
		$dom->resolveExternals = FALSE;
		$dom->validateOnParse = TRUE;
		$dom->preserveWhiteSpace = FALSE;
		$dom->strictErrorChecking = TRUE;
		$dom->recover = TRUE;

		set_error_handler(function($severity, $message) {
		  restore_error_handler();
		});
		@$dom->loadHTML($this->html);
		restore_error_handler();

		$this->errors = array_filter(libxml_get_errors(), function (\LibXMLError $error) {
			return !in_array((int)$error->code, ValidatorPanel::$ignoreErrors, TRUE);
		});
		libxml_clear_errors();
	}

}
